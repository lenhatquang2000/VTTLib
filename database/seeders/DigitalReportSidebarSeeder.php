<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalReportSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Parent Sidebar: "Tài liệu số" (parent_id = null)
        $parent = DB::table('sidebars')
            ->where('parent_id', null)
            ->where(function($q) {
                $q->where('name', 'Digital Library')
                  ->orWhere('name_vi', 'Tài liệu số');
            })->first();

        if (!$parent) {
            $this->command->error('Parent sidebar "Tài liệu số" not found. Please run DigitalSidebarSeeder first.');
            return;
        }

        $parentId = $parent->id;

        // Check if sidebar entry already exists for the digital reports route
        $existing = DB::table('sidebars')->where('route_name', 'admin.digital.reports.index')->first();

        if (!$existing) {
            $sidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentId,
                'name' => 'Digital Reports',
                'name_vi' => 'Báo cáo tài liệu số',
                'name_en' => 'Digital Reports',
                'route_name' => 'admin.digital.reports.index',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Created new Digital Reports sidebar item.');
        } else {
            $sidebarId = $existing->id;
            DB::table('sidebars')->where('id', $sidebarId)->update([
                'parent_id' => $parentId,
                'name' => 'Digital Reports',
                'name_vi' => 'Báo cáo tài liệu số',
                'name_en' => 'Digital Reports',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'is_active' => true,
                'order' => 6,
                'updated_at' => now(),
            ]);
            $this->command->info('Updated existing Digital Reports sidebar item.');
        }

        // Grant access to admin and root roles
        $roles = DB::table('roles')->whereIn('name', ['admin', 'root'])->get();
        
        foreach ($roles as $role) {
            // 1. Update role template (role_sidebars)
            $roleSidebarExists = DB::table('role_sidebars')
                ->where('role_id', $role->id)
                ->where('sidebar_id', $sidebarId)
                ->exists();
            
            if (!$roleSidebarExists) {
                DB::table('role_sidebars')->insert([
                    'role_id' => $role->id,
                    'sidebar_id' => $sidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2. Update actual user assignments (user_role_sidebars)
            $roleUsers = DB::table('role_user')->where('role_id', $role->id)->get();
            foreach ($roleUsers as $ru) {
                $userSidebarExists = DB::table('user_role_sidebars')
                    ->where('role_user_id', $ru->id)
                    ->where('sidebar_id', $sidebarId)
                    ->exists();
                
                if (!$userSidebarExists) {
                    DB::table('user_role_sidebars')->insert([
                        'role_user_id' => $ru->id,
                        'sidebar_id' => $sidebarId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Permissions granted successfully for Admin and Root roles.');
    }
}
