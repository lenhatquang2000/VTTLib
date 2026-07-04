<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OnlineDatabaseSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create parent menu for Content Management
        $parentMenu = DB::table('sidebars')
            ->where('name', 'Content Management')
            ->first();

        if (!$parentMenu) {
            $parentMenuId = DB::table('sidebars')->insertGetId([
                'name' => 'Content Management',
                'name_vi' => 'Quản lý nội dung',
                'name_en' => 'Content Management',
                'route_name' => null,
                'icon' => 'fas fa-newspaper',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $parentMenuId = $parentMenu->id;
        }

        // Check if Online Databases sidebar item already exists
        $existingMenu = DB::table('sidebars')
            ->where('name', 'Online Databases')
            ->where('parent_id', $parentMenuId)
            ->first();

        if (!$existingMenu) {
            $sidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentMenuId,
                'name' => 'Online Databases',
                'name_vi' => 'Quản lý CSDL trực tuyến',
                'name_en' => 'Manage Online Databases',
                'route_name' => 'admin.online-databases.index',
                'icon' => 'fas fa-database',
                'order' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $sidebarId = $existingMenu->id;
            DB::table('sidebars')
                ->where('id', $sidebarId)
                ->update([
                    'parent_id' => $parentMenuId,
                    'name_vi' => 'Quản lý CSDL trực tuyến',
                    'name_en' => 'Manage Online Databases',
                    'route_name' => 'admin.online-databases.index',
                    'icon' => 'fas fa-database',
                    'updated_at' => now(),
                ]);
        }

        // Assign to roles (role_sidebars)
        $roles = DB::table('roles')
            ->whereIn('name', ['admin', 'root'])
            ->get();

        foreach ($roles as $role) {
            $exists = DB::table('role_sidebars')
                ->where('role_id', $role->id)
                ->where('sidebar_id', $sidebarId)
                ->exists();

            if (!$exists) {
                DB::table('role_sidebars')->insert([
                    'role_id' => $role->id,
                    'sidebar_id' => $sidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Also assign to existing user role assignments (user_role_sidebars)
        foreach ($roles as $role) {
            $roleUsers = DB::table('role_user')
                ->where('role_id', $role->id)
                ->get();

            foreach ($roleUsers as $roleUser) {
                $exists = DB::table('user_role_sidebars')
                    ->where('role_user_id', $roleUser->id)
                    ->where('sidebar_id', $sidebarId)
                    ->exists();

                if (!$exists) {
                    DB::table('user_role_sidebars')->insert([
                        'role_user_id' => $roleUser->id,
                        'sidebar_id' => $sidebarId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Online Databases sidebar item added successfully!');
    }
}
