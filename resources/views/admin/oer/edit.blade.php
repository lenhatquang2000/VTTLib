@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Edit OER Resource') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Update open educational resource.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.oer.index') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('Back') }}</span>
            </a>
        </div>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <form action="{{ route('admin.oer.update', $resource) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <!-- Basic Information -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                    {{ __('Basic Information') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Title') }} <span class="text-destructive">*</span></label>
                        <input type="text" name="title" value="{{ $resource->title }}" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Resource Type') }} <span class="text-destructive">*</span></label>
                        <select name="resource_type" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="document" {{ $resource->resource_type == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="video" {{ $resource->resource_type == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="audio" {{ $resource->resource_type == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="interactive" {{ $resource->resource_type == 'interactive' ? 'selected' : '' }}>Interactive</option>
                            <option value="dataset" {{ $resource->resource_type == 'dataset' ? 'selected' : '' }}>Dataset</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Language') }}</label>
                        <input type="text" name="language" value="{{ $resource->language }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., en, vi, fr">
                    </div>
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Description') }}</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none">{{ $resource->description }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Author & Publisher -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-primary"></i>
                    {{ __('Author & Publisher') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Authors') }}</label>
                        <input type="text" name="authors[]" value="{{ is_array($resource->authors) ? implode(', ', $resource->authors) : $resource->authors }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Author name">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publisher') }}</label>
                        <input type="text" name="publisher" value="{{ $resource->publisher }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publish Year') }}</label>
                        <input type="number" name="publish_year" value="{{ $resource->publish_year }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="2024">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Format') }}</label>
                        <input type="text" name="format" value="{{ $resource->format }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., PDF, MP4">
                    </div>
                </div>
            </div>

            <!-- Classification -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-primary"></i>
                    {{ __('Classification') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Subjects') }}</label>
                        <input type="text" name="subjects[]" value="{{ is_array($resource->subjects) ? implode(', ', $resource->subjects) : $resource->subjects }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Subject area">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Educational Levels') }}</label>
                        <input type="text" name="educational_levels[]" value="{{ is_array($resource->educational_levels) ? implode(', ', $resource->educational_levels) : $resource->educational_levels }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., undergraduate, graduate">
                    </div>
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Keywords') }}</label>
                        <input type="text" name="keywords" value="{{ $resource->keywords }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Comma-separated keywords">
                    </div>
                </div>
            </div>

            <!-- License & Rights -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-primary"></i>
                    {{ __('License & Rights') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('License') }}</label>
                        <select name="license" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('Select License') }}</option>
                            <option value="CC BY" {{ $resource->license == 'CC BY' ? 'selected' : '' }}>CC BY (Attribution)</option>
                            <option value="CC BY-SA" {{ $resource->license == 'CC BY-SA' ? 'selected' : '' }}>CC BY-SA (Attribution-ShareAlike)</option>
                            <option value="CC BY-ND" {{ $resource->license == 'CC BY-ND' ? 'selected' : '' }}>CC BY-ND (Attribution-NoDerivs)</option>
                            <option value="CC BY-NC" {{ $resource->license == 'CC BY-NC' ? 'selected' : '' }}>CC BY-NC (Attribution-NonCommercial)</option>
                            <option value="CC BY-NC-SA" {{ $resource->license == 'CC BY-NC-SA' ? 'selected' : '' }}>CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)</option>
                            <option value="CC BY-NC-ND" {{ $resource->license == 'CC BY-NC-ND' ? 'selected' : '' }}>CC BY-NC-ND (Attribution-NonCommercial-NoDerivs)</option>
                            <option value="Public Domain" {{ $resource->license == 'Public Domain' ? 'selected' : '' }}>Public Domain</option>
                            <option value="Other" {{ $resource->license == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('License URL') }}</label>
                        <input type="url" name="license_url" value="{{ $resource->license_url }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="https://...">
                    </div>
                </div>
            </div>

            <!-- Files & Status -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4 text-primary"></i>
                    {{ __('Files & Media') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Left Column: File + Status (2/3 width) -->
                    <div class="sm:col-span-2 space-y-3">
                        <!-- Resource File -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Resource File') }}</label>
                            @if($resource->file_path)
                                <div class="flex items-center gap-2 p-2 rounded-sm bg-muted/50 border border-border text-[11px]">
                                    <i data-lucide="file" class="w-4 h-4 text-primary shrink-0"></i>
                                    <div class="min-w-0 flex-1">
                                        <div class="truncate text-muted-foreground">{{ $resource->file_name }}</div>
                                        <div class="text-muted-foreground/70">{{ number_format($resource->file_size / 1024 / 1024, 2) }} MB</div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="file" 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all file:mr-2 file:py-1 file:px-2 file:rounded-sm file:border-0 file:text-xs file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <p class="text-[10px] text-muted-foreground mt-1">Max 100MB</p>
                        </div>

                        <!-- Publication Status -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publication Status') }} <span class="text-destructive">*</span></label>
                            <select name="status" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <option value="draft" {{ $resource->status == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                <option value="published" {{ $resource->status == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column: Cover Image (1/3 width, match 2 items height) -->
                    <div class="space-y-1 flex flex-col">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Cover Image') }}</label>
                        
                        <!-- Cover Image Preview Box - Fixed height to match 2 items -->
                        <div class="relative group">
                            <!-- Preview Frame with Book Aspect Ratio (3:4) -->
                            <div id="coverPreviewBox" class="relative rounded-sm border-2 border-dashed border-border bg-muted/30 flex items-center justify-center overflow-hidden transition-all hover:border-primary/50 hover:bg-muted/50 cursor-pointer aspect-[3/4] h-auto" style="height: 200px;">
                                <!-- Current Cover or Fallback -->
                                @if($resource->cover_path)
                                    <img id="coverPreviewImg" src="{{ asset('storage/' . $resource->cover_path) }}" alt="Cover" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <i data-lucide="upload-cloud" class="w-5 h-5 text-white"></i>
                                    </div>
                                @else
                                    <div id="coverFallback" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5">
                                        <i data-lucide="image" class="w-8 h-8 text-muted-foreground"></i>
                                    </div>
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <i data-lucide="upload-cloud" class="w-5 h-5 text-white"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Hidden File Input -->
                        <input type="file" id="coverFileInput" name="cover" accept="image/*" class="hidden">

                        <!-- File Info -->
                        <p class="text-[10px] text-muted-foreground">Max 10MB</p>
                    </div>
                </div>
            </div>

            <!-- External Links -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-primary"></i>
                    {{ __('External Links') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('External Link') }}</label>
                        <input type="url" name="external_link" value="{{ $resource->external_link }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="https://...">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Source') }}</label>
                        <input type="text" name="source" value="{{ $resource->source }}" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Source name">
                    </div>
                </div>
            </div>

            

            <!-- Actions -->
            <div class="p-4 bg-muted/30 flex flex-col sm:flex-row justify-end gap-2">
                <a href="{{ route('admin.oer.index') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">{{ __('Cancel') }}</span>
                    <span class="sm:hidden">{{ __('Hủy bỏ') }}</span>
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">{{ __('Update Resource') }}</span>
                    <span class="sm:hidden">{{ __('Update') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        initializeCoverUpload();
    });

    function initializeCoverUpload() {
        const coverPreviewBox = document.getElementById('coverPreviewBox');
        const coverFileInput = document.getElementById('coverFileInput');
        const coverPreviewImg = document.getElementById('coverPreviewImg');
        const coverFallback = document.getElementById('coverFallback');

        if (!coverPreviewBox || !coverFileInput) return;

        // Click to upload
        coverPreviewBox.addEventListener('click', () => {
            coverFileInput.click();
        });

        // File input change
        coverFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                handleCoverFile(file);
            }
        });

        // Drag and drop
        coverPreviewBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            coverPreviewBox.classList.add('border-primary', 'bg-primary/5');
        });

        coverPreviewBox.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            coverPreviewBox.classList.remove('border-primary', 'bg-primary/5');
        });

        coverPreviewBox.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            coverPreviewBox.classList.remove('border-primary', 'bg-primary/5');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    coverFileInput.files = files;
                    handleCoverFile(file);
                } else {
                    alert('{{ __("Please drop an image file") }}');
                }
            }
        });
    }

    function handleCoverFile(file) {
        const coverPreviewBox = document.getElementById('coverPreviewBox');
        const coverFileInput = document.getElementById('coverFileInput');
        const coverPreviewImg = document.getElementById('coverPreviewImg');
        const coverFallback = document.getElementById('coverFallback');

        // Validate file size
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            alert('{{ __("File size must be less than 10MB") }}');
            coverFileInput.value = '';
            return;
        }

        // Read and preview
        const reader = new FileReader();
        reader.onload = (e) => {
            // Remove fallback if exists
            if (coverFallback) {
                coverFallback.style.display = 'none';
            }

            // Create or update preview image
            if (coverPreviewImg) {
                coverPreviewImg.src = e.target.result;
                coverPreviewImg.style.display = 'block';
            } else {
                const img = document.createElement('img');
                img.id = 'coverPreviewImg';
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                img.style.display = 'block';
                coverPreviewBox.innerHTML = img.outerHTML + 
                    '<div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">' +
                    '<div class="text-center">' +
                    '<i data-lucide="upload-cloud" class="w-6 h-6 text-white mx-auto mb-1"></i>' +
                    '<p class="text-xs text-white font-medium">{{ __("Change Cover") }}</p>' +
                    '</div></div>';
                lucide.createIcons();
            }
        };
        reader.readAsDataURL(file);

        // Reset drag styles
        coverPreviewBox.classList.remove('border-primary', 'bg-primary/5');
    }
</script>
@endsection
