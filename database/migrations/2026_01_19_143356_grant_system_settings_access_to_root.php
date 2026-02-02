<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Sidebar;
use App\Models\UserRoleSidebar;
use App\Models\RoleUser;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $rootRole = Role::where('name', 'root')->first();
        if (!$rootRole) return;

        $managementSidebar = Sidebar::where('name', 'System Management')->first();
        $settingsSidebar = Sidebar::where('name', 'System Settings')->first();

        if ($managementSidebar && $settingsSidebar) {
            // Get all role_user pivots for root role
            $roleUsers = RoleUser::where('role_id', $rootRole->id)->get();

            foreach ($roleUsers as $roleUser) {
                // Assign parent
                UserRoleSidebar::firstOrCreate([
                    'role_user_id' => $roleUser->id,
                    'sidebar_id' => $managementSidebar->id
                ]);

                // Assign child
                UserRoleSidebar::firstOrCreate([
                    'role_user_id' => $roleUser->id,
                    'sidebar_id' => $settingsSidebar->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert specifically as sidebar items might be manually managed later
    }
};
