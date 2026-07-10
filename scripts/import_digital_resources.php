<?php
/**
 * import_digital_resources.php
 * Import toan bo tai lieu so tu public/tailieuso vao database
 * 
 * Mo ta:
 *   - Quet tat ca thu muc con trong public/tailieuso/
 *   - Doc file meta.json trong moi thu muc
 *   - Copy PDF va anh bia vao storage
 *   - Luu thong tin vao bang digital_resources
 *   - Bo qua tai lieu da ton tai (khong tao ban ghi trung)
 * 
 * Chay: php scripts/import_digital_resources.php
 */
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DigitalFolder;
use App\Models\DigitalResource;

$baseDir = 'E:\\Workspace\\VTTU\\Laravel\\LibReadDb\\storage\\tailieuso3';
if (!is_dir($baseDir)) {
    echo "Thu muc khong ton tai: $baseDir" . PHP_EOL;
    exit(1);
}

// Lay tat ca cac thu muc con trong tailieuso3
$dirs = array_filter(glob($baseDir . '/*'), 'is_dir');

echo "Tim thay " . count($dirs) . " thu muc con trong $baseDir." . PHP_EOL;

// Dam bao cac thu muc luu tru trong storage ton tai
$storageResourceDir = storage_path('app/public/digital_resources');
$storageCoverDir = storage_path('app/public/covers');

if (!is_dir($storageResourceDir)) {
    mkdir($storageResourceDir, 0755, true);
}
if (!is_dir($storageCoverDir)) {
    mkdir($storageCoverDir, 0755, true);
}

$successCount = 0;
$skippedCount = 0;

// Mapping chinh xac ten thu_muc -> folder_id chinh thuc trong DB
$folderIdMap = [
    '5. Chính trị - Xã hội'  => 771,
    'Tài liệu chuyên ngành'   => 803,
    '2. Y học - Sức khỏe'     => 772,
    'Y học - Sức khỏe'        => 772,
    '3. Kinh tế - Luật'       => 779,
    'Kinh tế - Luật'          => 779,
    '4. Ngoại ngữ - Tin học'  => 780,
    'Ngoại ngữ - Tin học'     => 780,
    'Bài giảng VTTU'          => 788,
    'Khóa luận tốt nghiệp'   => 790,
    'Khoa Y'                   => 791,
    'Khoa Dược'               => 792,
    'Khoa Kinh tế - Luật'     => 793,
    'Công nghệ thông tin'     => 794,
];

foreach ($dirs as $dirPath) {
    $folderId = basename($dirPath);
    $metaFile = $dirPath . '/meta.json';

    if (!file_exists($metaFile)) {
        echo "Bo qua thu muc $folderId: Khong tim thay file meta.json" . PHP_EOL;
        $skippedCount++;
        continue;
    }

    $jsonContent = file_get_contents($metaFile);
    $meta = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Bo qua thu muc $folderId: loi decode JSON trong meta.json" . PHP_EOL;
        $skippedCount++;
        continue;
    }

    // Lay va lam sach cac truong metadata
    $title = isset($meta['title']) ? trim(rtrim(trim($meta['title']), '/\\')) : '';
    if (empty($title)) {
        echo "Bo qua thu muc $folderId: Tieu de bi trong." . PHP_EOL;
        $skippedCount++;
        continue;
    }

    // Xac dinh thu muc (Folder)
    $folderName = isset($meta['thu_muc']) ? trim($meta['thu_muc']) : '';
    if (empty($folderName)) {
        $folderName = 'Tài liệu chuyên ngành'; // fallback
    }

    // Tim folder tu mapping, neu khong co thi tim theo ten trong DB
    $folder = null;
    if (isset($folderIdMap[$folderName])) {
        $folder = DigitalFolder::find($folderIdMap[$folderName]);
    }

    if (!$folder) {
        // Fallback: tim theo ten
        $folder = DigitalFolder::where('folder_name', $folderName)->first();
    }

    if (!$folder) {
        // Fallback cuoi: tao moi neu khong tim thay
        $folderCode = strtoupper(\Illuminate\Support\Str::slug($folderName));
        if (strlen($folderCode) > 50) $folderCode = substr($folderCode, 0, 50);
        if (empty($folderCode)) $folderCode = 'GENERAL_' . time();

        $folder = DigitalFolder::create([
            'folder_code' => $folderCode,
            'folder_name' => $folderName,
            'description' => 'Thu muc tu dong tao khi import',
            'is_active'   => true,
            'language'    => 'vi'
        ]);
        echo "  -> Da tao thu muc moi: $folderName (ID: {$folder->id})" . PHP_EOL;
    }

    // Kiem tra trung lap: uu tien theo identifier (file_id), fallback theo title + folder_id
    $identifier = isset($meta['file_id']) ? trim($meta['file_id']) : null;
    $existing = null;

    if (!empty($identifier)) {
        $existing = DigitalResource::where('identifier', $identifier)->first();
    }
    if (!$existing) {
        $existing = DigitalResource::where('title', $title)
            ->where('folder_id', $folder->id)
            ->first();
    }

    if ($existing) {
        echo "  [bo qua] Da ton tai (ID {$existing->id}): '$title'" . PHP_EOL;
        $skippedCount++;
        continue;
    }

    // Xu ly File dinh kem (PDF)
    $pdfFileName = isset($meta['tap_tin_dinh_kem']) ? trim($meta['tap_tin_dinh_kem']) : '';
    if (empty($pdfFileName)) {
        $pdfs = glob($dirPath . '/*.pdf');
        if (count($pdfs) > 0) {
            $pdfFileName = basename($pdfs[0]);
        }
    }

    $sourcePdfPath = $dirPath . '/' . $pdfFileName;
    $targetPdfRelativePath = null;
    $fileSize = 0;

    if (!empty($pdfFileName) && file_exists($sourcePdfPath)) {
        $newPdfName = $folderId . '_' . $pdfFileName;
        $destPdfPath = $storageResourceDir . '/' . $newPdfName;
        if (!file_exists($destPdfPath)) {
            copy($sourcePdfPath, $destPdfPath);
        }
        $targetPdfRelativePath = 'digital_resources/' . $newPdfName;
        $fileSize = filesize($destPdfPath);
    } else {
        echo "  [loi] $folderId: Khong tim thay file PDF." . PHP_EOL;
        $skippedCount++;
        continue;
    }

    // Xu ly anh bia
    $coverName = isset($meta['hinh_anh_trang_bia']) ? trim($meta['hinh_anh_trang_bia']) : '';
    $sourceCoverPath = $dirPath . '/' . $coverName;
    $targetCoverRelativePath = null;

    if (!empty($coverName) && file_exists($sourceCoverPath)) {
        $newCoverName = $folderId . '_' . $coverName;
        $destCoverPath = $storageCoverDir . '/' . $newCoverName;
        if (!file_exists($destCoverPath)) {
            copy($sourceCoverPath, $destCoverPath);
        }
        $targetCoverRelativePath = 'covers/' . $newCoverName;
    }

    // Mapping ngon ngu
    $lang = isset($meta['ngon_ngu']) ? trim($meta['ngon_ngu']) : 'vie';
    $mappedLang = 'Tiếng Việt';
    if (strtolower($lang) === 'eng' || strtolower($lang) === 'en') {
        $mappedLang = 'Tiếng Anh';
    }

    // Mapping loai tai lieu
    $rawType = isset($meta['loai_tai_lieu']) ? trim($meta['loai_tai_lieu']) : 'b';
    $resourceType = 'Tài liệu số';
    if ($rawType === 'b') {
        $resourceType = 'Sách điện tử';
    }

    // Phan tach tac gia
    $authors = [];
    if (!empty($meta['tac_gia'])) {
        $authors = array_filter(array_map('trim', explode(',', $meta['tac_gia'])));
    }
    $secondaryAuthors = [];
    if (!empty($meta['tac_gia_phu'])) {
        $secondaryAuthors = array_filter(array_map('trim', explode(',', $meta['tac_gia_phu'])));
    }

    // Tao record
    DigitalResource::create([
        'folder_id'      => $folder->id,
        'title'          => $title,
        'resource_type'  => $resourceType,
        'file_path'      => $targetPdfRelativePath,
        'cover_path'     => $targetCoverRelativePath,
        'file_name'      => $pdfFileName,
        'file_size'      => $fileSize,
        'language'       => $mappedLang,
        'authors'        => $authors,
        'secondary_authors' => $secondaryAuthors,
        'subjects'       => !empty($meta['chu_de']) ? array_filter(array_map('trim', explode(',', $meta['chu_de']))) : [],
        'topics'         => [],
        'description'    => isset($meta['mo_ta']) ? trim($meta['mo_ta']) : null,
        'publisher'      => isset($meta['nha_xuat_ban']) ? trim($meta['nha_xuat_ban']) : null,
        'publish_year'   => isset($meta['ngay_phat_hanh']) ? trim($meta['ngay_phat_hanh']) : null,
        'format'         => isset($meta['dinh_dang']) ? trim($meta['dinh_dang']) : 'pdf',
        'identifier'     => isset($meta['file_id']) ? trim($meta['file_id']) : null,
        'source'         => isset($meta['nguon_goc']) ? trim($meta['nguon_goc']) : null,
        'link'           => isset($meta['lien_ket']) ? trim($meta['lien_ket']) : null,
        'copyright'      => isset($meta['ban_quyen']) ? trim($meta['ban_quyen']) : null,
        'pages'          => isset($meta['so_trang']) ? (int)$meta['so_trang'] : null,
        'status'         => 'published',
        'view_count'     => isset($meta['luot_xem']) ? (int)$meta['luot_xem'] : 0,
        'download_count' => isset($meta['luot_tai']) ? (int)$meta['luot_tai'] : 0,
        'created_by'     => 1,
    ]);

    echo "OK: '$title' -> '{$folder->folder_name}'" . PHP_EOL;
    $successCount++;
}

echo PHP_EOL . "=== KET QUA IMPORT ===" . PHP_EOL;
echo "Thanh cong : $successCount" . PHP_EOL;
echo "Bo qua/Loi: $skippedCount" . PHP_EOL;
