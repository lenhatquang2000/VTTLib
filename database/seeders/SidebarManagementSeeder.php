<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidebarManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Sidebar Management exists
        $existingManagement = DB::table('sidebars')
            ->where('name', 'Sidebar Management')
            ->first();

        if (!$existingManagement) {
            // Add Sidebar Management as child of System Management
            $managementSidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => 13, // System Management ID
                'name' => 'Sidebar Management',
                'route_name' => 'admin.sidebar.index',
                'icon' => '<i class="fas fa-bars-cog"></i>',
                'order' => 4, // After System Settings
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Grant access to admin and root roles
            $roles = DB::table('roles')
                ->whereIn('name', ['admin', 'root'])
                ->pluck('id');

            foreach ($roles as $roleId) {
                DB::table('user_role_sidebars')->insert([
                    'role_user_id' => $roleId,
                    'sidebar_id' => $managementSidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Sidebar Management sidebar item created successfully!');
        } else {
            // Update existing to ensure correct parent and order
            DB::table('sidebars')
                ->where('name', 'Sidebar Management')
                ->update([
                    'parent_id' => 13, // System Management ID
                    'order' => 4, // After System Settings
                ]);

            $this->command->info('Sidebar Management sidebar item updated successfully!');
        }
    }
}
