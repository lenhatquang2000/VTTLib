<?php
/**
 * check_import_result.php
 * Kiem tra ket qua import tai lieu so vao DB
 * Chay: php scripts/check_import_result.php
 */
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::select("
    SELECT f.id, f.folder_code, f.folder_name, f.parent_id,
           COUNT(r.id) as so_tai_lieu
    FROM digital_folders f
    LEFT JOIN digital_resources r ON r.folder_id = f.id AND r.deleted_at IS NULL
    GROUP BY f.id, f.folder_code, f.folder_name, f.parent_id
    ORDER BY f.parent_id IS NOT NULL, f.id
");

echo str_pad('ID', 6) . str_pad('Mã', 22) . str_pad('Thư mục', 38) . str_pad('Cha', 6) . "Tài liệu" . PHP_EOL;
echo str_repeat('-', 85) . PHP_EOL;

foreach ($rows as $r) {
    $indent = $r->parent_id ? '  └─ ' : '';
    echo str_pad($r->id, 6)
       . str_pad($r->folder_code, 22)
       . str_pad($indent . $r->folder_name, 38)
       . str_pad($r->parent_id ?? '-', 6)
       . $r->so_tai_lieu . PHP_EOL;
}

$total = DB::table('digital_resources')->whereNull('deleted_at')->count();
echo str_repeat('-', 85) . PHP_EOL;
echo "Tong tai lieu so trong DB: $total" . PHP_EOL;
