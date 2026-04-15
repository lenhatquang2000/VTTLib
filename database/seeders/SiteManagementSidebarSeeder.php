<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteManagementSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the System parent or create it
        $systemParent = DB::table('sidebars')
            ->where('name', 'System')
            ->first();

        if (!$systemParent) {
            // Create System parent if it doesn't exist
            $systemParentId = DB::table('sidebars')->insertGetId([
                'parent_id' => null,
                'name' => 'System',
                'route_name' => null,
                'icon' => 'fas fa-cogs',
                'order' => 900,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $systemParentId = $systemParent->id;
        }

        // Add Site Management menu item
        $siteManagementExists = DB::table('sidebars')
            ->where('name', 'Site Management')
            ->exists();

        if (!$siteManagementExists) {
            $siteManagementId = DB::table('sidebars')->insertGetId([
                'parent_id' => $systemParentId,
                'name' => 'Site Management',
                'route_name' => 'admin.site-nodes.index',
                'icon' => 'fas fa-sitemap',
                'order' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add sub-menu items
            $subMenus = [
                [
                    'name' => 'Site Structure',
                    'route_name' => 'admin.site-nodes.index',
                    'icon' => 'fas fa-sitemap',
                    'order' => 1,
                ],
                [
                    'name' => 'Tree Management',
                    'route_name' => 'admin.site-nodes.tree',
                    'icon' => 'fas fa-tree',
                    'order' => 2,
                ],
                [
                    'name' => 'Add Node',
                    'route_name' => 'admin.site-nodes.create',
                    'icon' => 'fas fa-plus',
                    'order' => 3,
                ],
            ];

            foreach ($subMenus as $index => $menu) {
                DB::table('sidebars')->insert([
                    'parent_id' => $siteManagementId,
                    'name' => $menu['name'],
                    'route_name' => $menu['route_name'],
                    'icon' => $menu['icon'],
                    'order' => $menu['order'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Assign to admin role - get admin role ID
        $adminRole = DB::table('roles')
            ->where('name', 'admin')
            ->first();

        if ($adminRole) {
            // Get the Site Management sidebar ID we just created
            $siteManagementSidebar = DB::table('sidebars')
                ->where('name', 'Site Management')
                ->first();

            if ($siteManagementSidebar) {
                // Check if already assigned
                $exists = DB::table('role_sidebars')
                    ->where('role_id', $adminRole->id)
                    ->where('sidebar_id', $siteManagementSidebar->id)
                    ->exists();

                if (!$exists) {
                    DB::table('role_sidebars')->insert([
                        'role_id' => $adminRole->id,
                        'sidebar_id' => $siteManagementSidebar->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Site Management menu added to sidebar successfully!');
    }
}
