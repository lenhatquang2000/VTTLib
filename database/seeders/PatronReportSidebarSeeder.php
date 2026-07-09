<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatronReportSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent: Patron Management (ID = 7)
        $parent = DB::table('sidebars')->where('id', 7)->first() 
            ?? DB::table('sidebars')->where('name', 'Patron Management')->first();
        $parentId = $parent ? $parent->id : 7;

        // Check if sidebar entry already exists for the reports route
        $existing = DB::table('sidebars')->where('route_name', 'admin.patrons.reports.index')->first();

        if (!$existing) {
            $sidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentId,
                'name' => 'Patron Reports',
                'name_vi' => 'Báo cáo bạn đọc',
                'name_en' => 'Patron Reports',
                'route_name' => 'admin.patrons.reports.index',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'order' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Created new Patron Reports sidebar item.');
        } else {
            $sidebarId = $existing->id;
            DB::table('sidebars')->where('id', $sidebarId)->update([
                'parent_id' => $parentId,
                'name' => 'Patron Reports',
                'name_vi' => 'Báo cáo bạn đọc',
                'name_en' => 'Patron Reports',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'is_active' => true,
                'updated_at' => now(),
            ]);
            $this->command->info('Updated existing Patron Reports sidebar item.');
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
