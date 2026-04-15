<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsManagementSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create parent menu for News Management
        $parentMenu = DB::table('sidebars')
            ->where('name', 'Content Management')
            ->first();

        if (!$parentMenu) {
            // Create Content Management parent menu
            $parentMenuId = DB::table('sidebars')->insertGetId([
                'name' => 'Content Management',
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

        // Check if News Management already exists
        $existingMenu = DB::table('sidebars')
            ->where('name', 'News Management')
            ->where('parent_id', $parentMenuId)
            ->first();

        if (!$existingMenu) {
            // Create News Management parent menu
            $newsManagementId = DB::table('sidebars')->insertGetId([
                'parent_id' => $parentMenuId,
                'name' => 'News Management',
                'route_name' => 'admin.news.index',
                'icon' => 'fas fa-newspaper',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $newsManagementId = $existingMenu->id;
        }

        // Add sub-menu items for News Management
        $subMenus = [
            [
                'name' => 'All News',
                'route_name' => 'admin.news.index',
                'icon' => 'fas fa-list',
                'order' => 1,
            ],
            [
                'name' => 'Create News',
                'route_name' => 'admin.news.create',
                'icon' => 'fas fa-plus',
                'order' => 2,
            ],
            [
                'name' => 'Categories',
                'route_name' => 'admin.news-categories.index',
                'icon' => 'fas fa-folder',
                'order' => 3,
            ],
            [
                'name' => 'Tags',
                'route_name' => 'admin.news-tags.index',
                'icon' => 'fas fa-tags',
                'order' => 4,
            ],
        ];

        foreach ($subMenus as $index => $menu) {
            // Check if sub-menu already exists
            $existingSubMenu = DB::table('sidebars')
                ->where('name', $menu['name'])
                ->where('parent_id', $newsManagementId)
                ->first();

            if (!$existingSubMenu) {
                DB::table('sidebars')->insert([
                    'parent_id' => $newsManagementId,
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
            // Get all News Management sidebar IDs
            $sidebarIds = DB::table('sidebars')
                ->where(function ($query) use ($newsManagementId) {
                    $query->where('id', $newsManagementId)
                          ->orWhere('parent_id', $newsManagementId);
                })
                ->pluck('id');

            // Assign all to admin role
            foreach ($sidebarIds as $sidebarId) {
                $exists = DB::table('role_sidebars')
                    ->where('role_id', $adminRole->id)
                    ->where('sidebar_id', $sidebarId)
                    ->exists();

                if (!$exists) {
                    DB::table('role_sidebars')->insert([
                        'role_id' => $adminRole->id,
                        'sidebar_id' => $sidebarId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('News Management menu added to sidebar successfully!');
    }
}
