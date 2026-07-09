<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogingReportSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent: Cataloging (Biên mục, ID = 6)
        $parent = DB::table('sidebars')->where('id', 6)->first() 
            ?? DB::table('sidebars')->where('name', 'Cataloging')->first();
        $parentId = $parent ? $parent->id : 6;

        // Check if sidebar entry already exists for the reports route
        $existing = DB::table('sidebars')->where('route_name', 'admin.marc.reports.index')->first();

        if (!$existing) {
            $sidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentId,
                'name' => 'Cataloging Reports',
                'name_vi' => 'Báo cáo Biên mục',
                'name_en' => 'Cataloging Reports',
                'route_name' => 'admin.marc.reports.index',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'order' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Created new Cataloging Reports sidebar item.');
        } else {
            $sidebarId = $existing->id;
            DB::table('sidebars')->where('id', $sidebarId)->update([
                'parent_id' => $parentId,
                'name' => 'Cataloging Reports',
                'name_vi' => 'Báo cáo Biên mục',
                'name_en' => 'Cataloging Reports',
                'icon' => '<i class="fas fa-file-invoice"></i>',
                'is_active' => true,
                'updated_at' => now(),
            ]);
            $this->command->info('Updated existing MARC Reports sidebar to Cataloging Reports under Cataloging parent.');
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
