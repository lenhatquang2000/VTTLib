<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Tìm parent "Quản lý bạn đọc" hoặc "Patron Management"
$parentSidebar = \App\Models\Sidebar::where('name', 'like', '%patron%')
    ->orWhere('name', 'like', '%bạn đọc%')
    ->first();

if (!$parentSidebar) {
    echo "Không tìm thấy parent sidebar. Vui lòng kiểm tra lại.\n";
    exit(1);
}

echo "Parent sidebar found: {$parentSidebar->name} (ID: {$parentSidebar->id})\n";

// Thêm các tabs con
$tabs = [
    [
        'name' => 'Danh sách chờ in thẻ',
        'route_name' => 'admin.patrons.print-queue.index',
        'icon' => '<i class="fas fa-print"></i>',
        'order' => 1
    ],
    [
        'name' => 'Lược sử khóa độc giả', 
        'route_name' => 'admin.patrons.lock-history.all',
        'icon' => '<i class="fas fa-lock"></i>',
        'order' => 2
    ],
    [
        'name' => 'Nhật ký hệ thống',
        'route_name' => 'admin.patrons.system-logs', 
        'icon' => '<i class="fas fa-history"></i>',
        'order' => 3
    ]
];

foreach ($tabs as $tab) {
    // Kiểm tra xem đã tồn tại chưa
    $existing = \App\Models\Sidebar::where('name', $tab['name'])
        ->where('parent_id', $parentSidebar->id)
        ->first();

    if ($existing) {
        echo "Tab '{$tab['name']}' đã tồn tại. Bỏ qua.\n";
        continue;
    }

    // Tạo mới
    $newTab = \App\Models\Sidebar::create([
        'parent_id' => $parentSidebar->id,
        'name' => $tab['name'],
        'route_name' => $tab['route_name'],
        'icon' => $tab['icon'],
        'order' => $tab['order'],
        'is_active' => 1
    ]);

    echo "Đã thêm tab '{$tab['name']}' (ID: {$newTab->id})\n";
}

echo "\nHoàn thành! Vui lòng refresh lại trang để thấy các tabs mới.\n";
