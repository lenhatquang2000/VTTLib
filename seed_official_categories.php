<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DigitalFolder;
use Illuminate\Support\Facades\DB;

try {
    echo "Dang xoa du lieu cu trong digital_resources va digital_folders..." . PHP_EOL;
    // Disable foreign key checks tam thoi de truncate (TRUNCATE auto-commits, khong dung transaction)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('digital_resources')->truncate();
    DB::table('digital_folders')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $categories = [
        [
            'id' => 771,
            'folder_code' => '5-CHINH-TRI-XA-HOI',
            'folder_name' => '5. Chính trị - Xã hội',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 803,
            'folder_code' => 'TAI-LIEU-CHUYEN-NGANH',
            'folder_name' => 'Tài liệu chuyên ngành',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 772,
            'folder_code' => '772',
            'folder_name' => '2. Y học - Sức khỏe',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 779,
            'folder_code' => '774',
            'folder_name' => '3. Kinh tế - Luật',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 780,
            'folder_code' => '775',
            'folder_name' => '4. Ngoại ngữ - Tin học',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 788,
            'folder_code' => 'BG',
            'folder_name' => 'Bài giảng VTTU',
            'parent_id' => null,
            'description' => 'Danh mục gốc',
        ],
        [
            'id' => 790,
            'folder_code' => 'KLTN',
            'folder_name' => 'Khóa luận tốt nghiệp',
            'parent_id' => null,
            'description' => 'Danh mục gốc (Cha của 791, 792, 793, 794)',
        ],
        [
            'id' => 791,
            'folder_code' => 'KY',
            'folder_name' => 'Khoa Y',
            'parent_id' => 790,
            'description' => 'Thuộc danh mục Khóa luận tốt nghiệp',
        ],
        [
            'id' => 792,
            'folder_code' => 'KD',
            'folder_name' => 'Khoa Dược',
            'parent_id' => 790,
            'description' => 'Thuộc danh mục Khóa luận tốt nghiệp',
        ],
        [
            'id' => 793,
            'folder_code' => 'KKTL',
            'folder_name' => 'Khoa Kinh tế - Luật',
            'parent_id' => 790,
            'description' => 'Thuộc danh mục Khóa luận tốt nghiệp',
        ],
        [
            'id' => 794,
            'folder_code' => 'klcntt',
            'folder_name' => 'Công nghệ thông tin',
            'parent_id' => 790,
            'description' => 'Thuộc danh mục Khóa luận tốt nghiệp',
        ],
        [
            'id' => 801,
            'folder_code' => 'TMKT',
            'folder_name' => 'Folder Test',
            'parent_id' => null,
            'description' => 'Folder Test',
        ],
    ];

    echo "Dang chen danh muc chinh thuc..." . PHP_EOL;
    foreach ($categories as $cat) {
        DB::table('digital_folders')->insert([
            'id' => $cat['id'],
            'folder_code' => $cat['folder_code'],
            'folder_name' => $cat['folder_name'],
            'description' => $cat['description'],
            'parent_id' => $cat['parent_id'],
            'sort_order' => 0,
            'is_active' => true,
            'language' => 'vi',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Da tao danh muc: ID {$cat['id']} | {$cat['folder_name']}" . PHP_EOL;
    }

    echo "=== Dong bo danh muc chinh thuc thanh cong! ===" . PHP_EOL;
} catch (\Exception $e) {
    echo "LOI: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
