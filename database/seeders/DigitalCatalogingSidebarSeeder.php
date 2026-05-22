<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalCatalogingSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sidebar cha "Biên mục" có ID là 6 theo dữ liệu thực tế
        $parentId = 6;

        // Kiểm tra lại trong DB cho chắc chắn
        $parent = DB::table('sidebars')->where('id', $parentId)->first();

        if (!$parent) {
            $this->command->error('Không tìm thấy sidebar cha có ID là 6!');
            return;
        }

        // 2. Kiểm tra xem đã tồn tại chưa
        $existing = DB::table('sidebars')
            ->where('parent_id', $parentId)
            ->where('name', 'Digital Cataloging')
            ->first();

        if ($existing) {
            // Cập nhật route_name nếu đã tồn tại
            DB::table('sidebars')->where('id', $existing->id)->update([
                'route_name' => 'admin.digital-cataloging.index',
                'updated_at' => now(),
            ]);
            $this->command->info('Đã cập nhật route cho sidebar "Biên mục tài liệu số".');
            return;
        }

        // 3. Lấy order lớn nhất của con để xếp xuống cuối
        $maxOrder = DB::table('sidebars')
            ->where('parent_id', $parentId)
            ->max('order') ?? 0;

        // 4. Thêm sidebar mới
        $sidebarId = DB::table('sidebars')->insertGetId([
            'parent_id' => $parentId,
            'name' => 'Digital Cataloging',
            'name_vi' => 'Biên mục tài liệu số',
            'name_en' => 'Digital Cataloging',
            'route_name' => 'admin.digital-cataloging.index',
            'icon' => '<i class="fas fa-file-alt"></i>',
            'order' => $maxOrder + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Cấp quyền cho các role admin/root
        $roleIds = DB::table('roles')
            ->whereIn('name', ['admin', 'root'])
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            $roleUserIds = DB::table('role_user')->where('role_id', $roleId)->pluck('id');
            foreach ($roleUserIds as $roleUserId) {
                DB::table('user_role_sidebars')->insertOrIgnore([
                    'role_user_id' => $roleUserId,
                    'sidebar_id' => $sidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Đã thêm sidebar "Biên mục tài liệu số" vào mục "Biên mục" thành công!');
    }
}
