<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sidebar;
use App\Models\UserRoleSidebar;

class MarcFeaturesSeeder extends Seeder
{
    public function run()
    {
        // Create MARC Import sidebar item
        $marcImportTab = Sidebar::create([
            'name' => 'MARC Import',
            'route_name' => 'admin.marc.import.index',
            'icon' => '<i class="fas fa-file-excel"></i>',
            'order' => 4,
            'parent_id' => null
        ]);

        // Create MARC Reports sidebar item
        $marcReportsTab = Sidebar::create([
            'name' => 'MARC Reports',
            'route_name' => 'admin.marc.reports.index',
            'icon' => '<i class="fas fa-chart-bar"></i>',
            'order' => 5,
            'parent_id' => null
        ]);

        // Assign to admin role (assuming role_id 1 is admin)
        $adminRoleId = 1; // Adjust based on your actual admin role ID
        
        UserRoleSidebar::create([
            'role_user_id' => $adminRoleId,
            'sidebar_id' => $marcImportTab->id
        ]);

        UserRoleSidebar::create([
            'role_user_id' => $adminRoleId,
            'sidebar_id' => $marcReportsTab->id
        ]);

        $this->command->info('MARC features sidebar items created successfully!');
    }
}
