<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create/Update Parent Sidebar: "Tài liệu số" (Digital Library)
        $parentExisting = DB::table('sidebars')
            ->where('parent_id', null)
            ->where(function($q) {
                $q->where('name', 'Digital Library')
                  ->orWhere('name_vi', 'Tài liệu số');
            })->first();

        if (!$parentExisting) {
            $parentId = DB::table('sidebars')->insertGetId([
                'parent_id' => null,
                'name' => 'Digital Library',
                'name_vi' => 'Tài liệu số',
                'name_en' => 'Digital Library',
                'route_name' => '#',
                'icon' => '<i class="fas fa-file-pdf"></i>',
                'order' => 42,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Created Parent Sidebar: Tài liệu số.');
        } else {
            $parentId = $parentExisting->id;
            DB::table('sidebars')->where('id', $parentId)->update([
                'name' => 'Digital Library',
                'name_vi' => 'Tài liệu số',
                'name_en' => 'Digital Library',
                'icon' => '<i class="fas fa-file-pdf"></i>',
                'is_active' => true,
                'updated_at' => now(),
            ]);
            $this->command->info('Updated Parent Sidebar: Tài liệu số.');
        }

        // Define Child Sidebar Items under Parent "Tài liệu số"
        $children = [
            [
                'name' => 'Digital Cataloging',
                'name_vi' => 'Biên mục tài liệu số',
                'name_en' => 'Digital Cataloging',
                'route_name' => 'admin.digital-cataloging.index',
                'icon' => '<i class="fas fa-edit"></i>',
                'order' => 1
            ],
            [
                'name' => 'Digital Categories',
                'name_vi' => 'Danh mục tài liệu số',
                'name_en' => 'Digital Categories',
                'route_name' => 'admin.digital-categories.index',
                'icon' => '<i class="fas fa-tags"></i>',
                'order' => 2
            ],
            [
                'name' => 'Digital Documents',
                'name_vi' => 'Danh sách tài liệu số',
                'name_en' => 'Digital Documents',
                'route_name' => 'admin.digital-documents.index',
                'icon' => '<i class="fas fa-file-alt"></i>',
                'order' => 3
            ],
            [
                'name' => 'Digital Resources',
                'name_vi' => 'Kho tập tin số',
                'name_en' => 'Digital Resources',
                'route_name' => 'admin.digital-resources.index',
                'icon' => '<i class="fas fa-folder-open"></i>',
                'order' => 4
            ],
            [
                'name' => 'Open Educational Resources',
                'name_vi' => 'Học liệu mở (OER)',
                'name_en' => 'Open Educational Resources',
                'route_name' => 'admin.oer.index',
                'icon' => '<i class="fas fa-book-open"></i>',
                'order' => 5
            ]
        ];

        // 2. Insert/Update Child Sidebar Items
        $sidebarIds = [$parentId];
        foreach ($children as $c) {
            $existingChild = DB::table('sidebars')->where('route_name', $c['route_name'])->first();
            
            if (!$existingChild) {
                $childId = DB::table('sidebars')->insertGetId([
                    'parent_id' => $parentId,
                    'name' => $c['name'],
                    'name_vi' => $c['name_vi'],
                    'name_en' => $c['name_en'],
                    'route_name' => $c['route_name'],
                    'icon' => $c['icon'],
                    'order' => $c['order'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sidebarIds[] = $childId;
                $this->command->info("Created child sidebar item: {$c['name_vi']}");
            } else {
                $childId = $existingChild->id;
                DB::table('sidebars')->where('id', $childId)->update([
                    'parent_id' => $parentId, // Move under the new parent!
                    'name' => $c['name'],
                    'name_vi' => $c['name_vi'],
                    'name_en' => $c['name_en'],
                    'icon' => $c['icon'],
                    'order' => $c['order'],
                    'is_active' => true,
                    'updated_at' => now(),
                ]);
                $sidebarIds[] = $childId;
                $this->command->info("Updated and moved child sidebar item: {$c['name_vi']}");
            }
        }

        // 3. Grant privileges to admin and root roles for all these sidebar entries
        $roles = DB::table('roles')->whereIn('name', ['admin', 'root'])->get();
        
        foreach ($roles as $role) {
            foreach ($sidebarIds as $sId) {
                // Template mapping (role_sidebars)
                $roleSidebarExists = DB::table('role_sidebars')
                    ->where('role_id', $role->id)
                    ->where('sidebar_id', $sId)
                    ->exists();
                
                if (!$roleSidebarExists) {
                    DB::table('role_sidebars')->insert([
                        'role_id' => $role->id,
                        'sidebar_id' => $sId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // User active mapping (user_role_sidebars)
                $roleUsers = DB::table('role_user')->where('role_id', $role->id)->get();
                foreach ($roleUsers as $ru) {
                    $userSidebarExists = DB::table('user_role_sidebars')
                        ->where('role_user_id', $ru->id)
                        ->where('sidebar_id', $sId)
                        ->exists();
                    
                    if (!$userSidebarExists) {
                        DB::table('user_role_sidebars')->insert([
                            'role_user_id' => $ru->id,
                            'sidebar_id' => $sId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Assigned administrator privileges successfully.');
    }
}
