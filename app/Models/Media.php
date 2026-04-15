<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'path',
        'url',
        'alt_text',
        'description',
        'caption',
        'uploaded_by',
        'type',
        'width',
        'height',
        'duration',
        'metadata',
        'is_public'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration' => 'integer'
    ];

    /**
     * Relationships
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scopes
     */
    public function scopeImages(Builder $query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos(Builder $query)
    {
        return $query->where('type', 'video');
    }

    public function scopeAudios(Builder $query)
    {
        return $query->where('type', 'audio');
    }

    public function scopeDocuments(Builder $query)
    {
        return $query->where('type', 'document');
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate(Builder $query)
    {
        return $query->where('is_public', false);
    }

    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('filename', 'like', "%{$search}%")
              ->orWhere('original_name', 'like', "%{$search}%")
              ->orWhere('alt_text', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'image' => 'Hình ảnh',
            'video' => 'Video',
            'audio' => 'Audio',
            'document' => 'Tài liệu',
            'other' => 'Khác',
            default => 'Không xác định'
        };
    }

    public function getIsImageAttribute()
    {
        return $this->type === 'image';
    }

    public function getIsVideoAttribute()
    {
        return $this->type === 'video';
    }

    public function getIsAudioAttribute()
    {
        return $this->type === 'audio';
    }

    public function getIsDocumentAttribute()
    {
        return $this->type === 'document';
    }

    public function getDimensionsAttribute()
    {
        if ($this->width && $this->height) {
            return "{$this->width}x{$this->height}";
        }
        return null;
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration) {
            $minutes = floor($this->duration / 60);
            $seconds = $this->duration % 60;
            return sprintf("%02d:%02d", $minutes, $seconds);
        }
        return null;
    }

    /**
     * Methods
     */
    public function getThumbnailUrl($width = 300, $height = 200)
    {
        if ($this->isImage) {
            // For images, you could implement thumbnail generation
            return $this->url;
        }
        
        // Return placeholder for non-images
        return asset('images/file-type-icons/' . $this->type . '.png');
    }

    public function getIconClass()
    {
        return match($this->type) {
            'image' => 'fas fa-image',
            'video' => 'fas fa-video',
            'audio' => 'fas fa-music',
            'document' => 'fas fa-file-alt',
            'other' => 'fas fa-file',
            default => 'fas fa-question-circle'
        };
    }

    public function canBeDeleted()
    {
        // Check if media is being used in other models
        // This would need to be implemented based on your specific use cases
        return true;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->path)) {
            return Storage::delete($this->path);
        }
        return true;
    }

    public function getPublicUrl()
    {
        if ($this->is_public) {
            return $this->url;
        }
        
        // Generate temporary signed URL for private files
        return Storage::temporaryUrl($this->path, now()->addMinutes(30));
    }

    /**
     * Static methods
     */
    public static function detectFileType($mimeType, $extension)
    {
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $videoTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv'];
        $audioTypes = ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a', 'audio/flac'];
        $documentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        
        if (in_array($mimeType, $imageTypes)) {
            return 'image';
        } elseif (in_array($mimeType, $videoTypes)) {
            return 'video';
        } elseif (in_array($mimeType, $audioTypes)) {
            return 'audio';
        } elseif (in_array($mimeType, $documentTypes)) {
            return 'document';
        }
        
        return 'other';
    }

    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'images' => self::images()->count(),
            'videos' => self::videos()->count(),
            'audios' => self::audios()->count(),
            'documents' => self::documents()->count(),
            'total_size' => self::sum('size'),
            'public' => self::public()->count(),
            'private' => self::private()->count(),
        ];
    }

    public static function getRecentMedia($limit = 10, $type = null)
    {
        $query = self::with('uploader')->latest();
        
        if ($type) {
            $query->byType($type);
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if (empty($media->type)) {
                $media->type = self::detectFileType($media->mime_type, $media->extension);
            }
            
            if (empty($media->uploaded_by) && auth()->check()) {
                $media->uploaded_by = auth()->id();
            }
        });

        static::deleting(function ($media) {
            // Delete the actual file
            $media->deleteFile();
        });
    }
}
