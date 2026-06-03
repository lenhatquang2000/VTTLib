<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OERSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent ID for Content Management is 45 based on previous check
        $parentId = 45;

        // Check if Tài Liệu Mở exists
        $existing = DB::table('sidebars')
            ->where('name', 'Tài Liệu Mở')
            ->first();

        if (!$existing) {
            $sidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentId,
                'name' => 'Tài Liệu Mở',
                'name_vi' => 'Tài Liệu Mở (OER)',
                'name_en' => 'Open Educational Resources',
                'route_name' => 'admin.oer.index',
                'icon' => '<i class="fas fa-book-open"></i>',
                'order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Grant access to admin and root roles
            $roles = DB::table('roles')
                ->whereIn('name', ['admin', 'root'])
                ->pluck('id');

            foreach ($roles as $roleId) {
                // Check if already exists to avoid duplicate
                $exists = DB::table('user_role_sidebars')
                    ->where('role_user_id', $roleId)
                    ->where('sidebar_id', $sidebarId)
                    ->exists();
                
                if (!$exists) {
                    DB::table('user_role_sidebars')->insert([
                        'role_user_id' => $roleId,
                        'sidebar_id' => $sidebarId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            // Update existing
            DB::table('sidebars')
                ->where('id', $existing->id)
                ->update([
                    'parent_id' => $parentId,
                    'route_name' => 'admin.oer.index',
                    'icon' => '<i class="fas fa-book-open"></i>',
                    'updated_at' => now(),
                ]);
        }
    }
}
