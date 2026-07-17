<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function getExtensionFromBinary($binaryData, $default = 'bin') {
    if (strlen($binaryData) < 4) {
        return $default;
    }
    $head = substr($binaryData, 0, 4);
    if (strpos($head, "\xFF\xD8\xFF") === 0) {
        return 'jpg';
    }
    if (strpos($head, "\x89PNG") === 0) {
        return 'png';
    }
    if (strpos($head, "GIF8") === 0) {
        return 'gif';
    }
    if (strpos($head, "%PDF") === 0) {
        return 'pdf';
    }
    if (strpos($head, "ID3") === 0 || strpos($head, "\xFF\xFB") === 0) {
        return 'mp3';
    }
    return $default;
}

try {
    echo "Connecting to old database (SQL Server)...\n";
    $oldDb = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Clearing/truncating target tables in MySQL (vttu_lib)...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('news_tag')->truncate();
    DB::table('news')->truncate();
    DB::table('news_categories')->truncate();
    DB::table('news_tags')->truncate();
    DB::table('news_article_types')->truncate();
    DB::table('news_authors')->truncate();
    DB::table('news_media')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // 1. Sync Article Types
    echo "Syncing Article Types (CUSTOMERID = 'DEFAULT')...\n";
    $stmt = $oldDb->query("SELECT * FROM PORTAL_NEWS_ARTICLETYPE WHERE CUSTOMERID = 'DEFAULT'");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($types as $type) {
        DB::table('news_article_types')->insert([
            'id' => $type['ARTICLETYPEID'],
            'name' => $type['NAME'],
            'friendly_name' => $type['FRIENDLYNAME'] ?? Str::slug($type['NAME']),
            'description' => $type['DESC'],
            'customer_id' => $type['CUSTOMERID'],
            'created_at' => $type['CREATED'] ? \Carbon\Carbon::parse($type['CREATED']) : now(),
            'updated_at' => $type['MODIFIED'] ? \Carbon\Carbon::parse($type['MODIFIED']) : now(),
        ]);
        echo "  - Added Article Type: {$type['NAME']} (ID: {$type['ARTICLETYPEID']})\n";
    }

    // 2. Sync Authors
    echo "Syncing Authors (CUSTOMERID = 'DEFAULT')...\n";
    $stmt = $oldDb->query("SELECT * FROM PORTAL_NEWS_AUTHOR WHERE CUSTOMERID = 'DEFAULT'");
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($authors as $author) {
        DB::table('news_authors')->insert([
            'id' => $author['AUTHORID'],
            'name' => $author['NAME'],
            'description' => $author['DESC'],
            'customer_id' => $author['CUSTOMERID'],
            'created_at' => $author['CREATED'] ? \Carbon\Carbon::parse($author['CREATED']) : now(),
            'updated_at' => $author['MODIFIED'] ? \Carbon\Carbon::parse($author['MODIFIED']) : now(),
        ]);
        echo "  - Added Author: {$author['NAME']} (ID: {$author['AUTHORID']})\n";
    }

    // 3. Sync Categories
    echo "Syncing Categories/Structures (CUSTOMERID = 'DEFAULT')...\n";
    $stmt = $oldDb->query("SELECT * FROM PORTAL_NEWS_STRUCTURE WHERE CUSTOMERID = 'DEFAULT'");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($categories as $cat) {
        $parent_id = trim($cat['PARENTID']);
        if ($parent_id === '0' || $parent_id === '') {
            $parent_id = null;
        }

        DB::table('news_categories')->insert([
            'id' => $cat['STRUCTUREID'],
            'name' => $cat['NAME'],
            'slug' => $cat['FRIENDLYNAME'] ?? Str::slug($cat['NAME']),
            'description' => $cat['DESC'],
            'parent_id' => $parent_id,
            'sort_order' => $cat['INDEX'] ?? 0,
            'is_active' => $cat['ISDISPLAY'] ?? true,
            'language' => 'vi',
            'customer_id' => $cat['CUSTOMERID'],
            'created_at' => $cat['CREATED'] ? \Carbon\Carbon::parse($cat['CREATED']) : now(),
            'updated_at' => $cat['MODIFIED'] ? \Carbon\Carbon::parse($cat['MODIFIED']) : now(),
        ]);
        echo "  - Added Category: {$cat['NAME']} (ID: {$cat['STRUCTUREID']})\n";
    }

    // Prepare Storage Directories
    $directories = [
        storage_path('app/public/news/images'),
        storage_path('app/public/news/audio'),
        storage_path('app/public/news/attachments'),
        storage_path('app/public/news/media')
    ];
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // 4. Sync News Items
    echo "Syncing News Items (CUSTOMERID = 'DEFAULT')...\n";
    // We select ITEMIMG, AUDIO, ATTACHMENT directly. Using PDO statement to stream columns
    $stmt = $oldDb->query("SELECT ITEMID, CATEGORYID, AUTHORID, ARTICLETYPEID, STRUCTUREID, TITLE, FRIENDLYNAME, ITEMSHORTCONTENT, CONTENT, STATUS, TAG, CREATED, MODIFIED, PUBLISHDATE, VIEWCOUNT, ISDISPLAY, ITEMIMG, AUDIO, ATTACHMENT FROM PORTAL_NEWS_ITEMS WHERE CUSTOMERID = 'DEFAULT'");
    
    $newsItemsSynced = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemId = $row['ITEMID'];
        
        // Resolve category_id from STRUCTUREID (e.g. ",49," -> 49)
        $category_id = null;
        if (!empty($row['STRUCTUREID'])) {
            $trimmedStr = trim($row['STRUCTUREID'], ',');
            if (!empty($trimmedStr)) {
                $ids = explode(',', $trimmedStr);
                $firstId = intval(trim($ids[0]));
                // Verify category exists
                if (DB::table('news_categories')->where('id', $firstId)->exists()) {
                    $category_id = $firstId;
                }
            }
        }

        // Map status (3 = published, others default to draft)
        $status = 'draft';
        if ($row['STATUS'] == 3 && $row['ISDISPLAY'] == 1) {
            $status = 'published';
        }

        // Process featured image
        $featuredImage = null;
        if (!empty($row['ITEMIMG'])) {
            $ext = getExtensionFromBinary($row['ITEMIMG'], 'jpg');
            $fileName = "image_{$itemId}.{$ext}";
            $filePath = storage_path("app/public/news/images/{$fileName}");
            file_put_contents($filePath, $row['ITEMIMG']);
            $featuredImage = "storage/news/images/{$fileName}";
        }

        // Create News record
        DB::table('news')->insert([
            'id' => $itemId,
            'title' => $row['TITLE'],
            'slug' => !empty($row['FRIENDLYNAME']) ? $row['FRIENDLYNAME'] : Str::slug($row['TITLE']) . '-' . $itemId,
            'summary' => $row['ITEMSHORTCONTENT'],
            'content' => $row['CONTENT'],
            'featured_image' => $featuredImage,
            'category_id' => $category_id,
            'author_id' => null, // point to users table, we leave null or assign admin
            'news_author_id' => $row['AUTHORID'],
            'article_type_id' => $row['ARTICLETYPEID'],
            'old_item_id' => $itemId,
            'status' => $status,
            'sort_order' => 0,
            'published_at' => $row['PUBLISHDATE'] ? \Carbon\Carbon::parse($row['PUBLISHDATE']) : null,
            'view_count' => $row['VIEWCOUNT'] ?? 0,
            'language' => 'vi',
            'customer_id' => 'DEFAULT',
            'created_at' => $row['CREATED'] ? \Carbon\Carbon::parse($row['CREATED']) : now(),
            'updated_at' => $row['MODIFIED'] ? \Carbon\Carbon::parse($row['MODIFIED']) : now(),
        ]);

        // Process tag field
        if (!empty($row['TAG'])) {
            $tagsList = explode(',', $row['TAG']);
            foreach ($tagsList as $tagName) {
                $tagName = trim($tagName);
                if ($tagName !== '') {
                    $tagName = mb_substr($tagName, 0, 50);
                    $slug = mb_substr(Str::slug($tagName), 0, 50);
                    // Find or create tag
                    $tagId = DB::table('news_tags')->where('slug', $slug)->value('id');
                    if (!$tagId) {
                        $tagId = DB::table('news_tags')->insertGetId([
                            'name' => $tagName,
                            'slug' => $slug,
                            'is_active' => true,
                            'language' => 'vi',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    // Insert pivot
                    DB::table('news_tag')->insert([
                        'news_id' => $itemId,
                        'tag_id' => $tagId
                    ]);
                }
            }
        }

        // Process audio attachment
        if (!empty($row['AUDIO'])) {
            $audioExt = 'mp3';
            $audioFileName = "audio_{$itemId}.{$audioExt}";
            $audioPath = storage_path("app/public/news/audio/{$audioFileName}");
            file_put_contents($audioPath, $row['AUDIO']);

            DB::table('news_media')->insert([
                'news_id' => $itemId,
                'media_name' => "Audio Attachment",
                'media_extension' => $audioExt,
                'media_type' => 2, // 2 = audio
                'file_path' => "storage/news/audio/{$audioFileName}",
                'is_display' => true,
                'customer_id' => 'DEFAULT',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Process document attachment
        if (!empty($row['ATTACHMENT'])) {
            $docExt = getExtensionFromBinary($row['ATTACHMENT'], 'pdf');
            $docFileName = "doc_{$itemId}.{$docExt}";
            $docPath = storage_path("app/public/news/attachments/{$docFileName}");
            file_put_contents($docPath, $row['ATTACHMENT']);

            DB::table('news_media')->insert([
                'news_id' => $itemId,
                'media_name' => "Document Attachment",
                'media_extension' => $docExt,
                'media_type' => 3, // 3 = doc
                'file_path' => "storage/news/attachments/{$docFileName}",
                'is_display' => true,
                'customer_id' => 'DEFAULT',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $newsItemsSynced++;
        if ($newsItemsSynced % 10 == 0) {
            echo "  - Synced $newsItemsSynced news items...\n";
        }
    }
    echo "  - Successfully synced total of $newsItemsSynced news items.\n";

    // 5. Sync PORTAL_NEWS_MEDIA items
    echo "Syncing news media gallery attachments (PORTAL_NEWS_MEDIA)...\n";
    $stmtMedia = $oldDb->query("SELECT MEDIAID, MEDIANAME, MEDIAEX, MEDIATYPE, MEDIANOTE, ITEMID, MEDIACONTENT, ISDISPLAY, CUSTOMERID FROM PORTAL_NEWS_MEDIA WHERE CUSTOMERID = 'DEFAULT'");
    
    $mediaSyncedCount = 0;
    while ($mediaRow = $stmtMedia->fetch(PDO::FETCH_ASSOC)) {
        // Verify target news item exists in synced list
        $news_id = $mediaRow['ITEMID'];
        if (!DB::table('news')->where('id', $news_id)->exists()) {
            continue;
        }

        $mediaId = $mediaRow['MEDIAID'];
        $ext = !empty($mediaRow['MEDIAEX']) ? trim($mediaRow['MEDIAEX'], '.') : getExtensionFromBinary($mediaRow['MEDIACONTENT'], 'jpg');
        $mediaFileName = "media_{$mediaId}.{$ext}";
        $mediaPath = storage_path("app/public/news/media/{$mediaFileName}");
        
        if (!empty($mediaRow['MEDIACONTENT'])) {
            file_put_contents($mediaPath, $mediaRow['MEDIACONTENT']);
            
            DB::table('news_media')->insert([
                'id' => $mediaId,
                'news_id' => $news_id,
                'media_name' => $mediaRow['MEDIANAME'] ?? "Media File",
                'media_extension' => $ext,
                'media_type' => $mediaRow['MEDIATYPE'] ?? 1, // 1 = image/general
                'media_note' => $mediaRow['MEDIANOTE'],
                'file_path' => "storage/news/media/{$mediaFileName}",
                'is_display' => $mediaRow['ISDISPLAY'] ?? true,
                'customer_id' => $mediaRow['CUSTOMERID'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $mediaSyncedCount++;
        }
    }
    echo "  - Successfully synced total of $mediaSyncedCount media items.\n";
    echo "\n=== ALL NEWS SYNC OPERATIONS COMPLETED SUCCESSFULLY! ===\n";

} catch (\Exception $e) {
    echo "FATAL ERROR during sync: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
