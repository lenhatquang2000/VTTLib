<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MarcExportSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if MARC Export already exists
        $existingExport = DB::table('sidebars')
            ->where('name', 'MARC Export')
            ->first();

        if (!$existingExport) {
            // Add MARC Export as top-level sidebar item
            $exportSidebarId = DB::table('sidebars')->insertGetId([
                'parent_id' => null,
                'name' => 'MARC Export',
                'route_name' => 'admin.marc.export.index',
                'icon' => '<i class="fas fa-download"></i>',
                'order' => 6, // After existing MARC items
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
                    'sidebar_id' => $exportSidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('MARC Export sidebar item created successfully!');
        } else {
            $this->command->info('MARC Export sidebar item already exists.');
        }
    }
}
