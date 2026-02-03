<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sidebar;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\UserRoleSidebar;
use App\Models\RoleSidebar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $systemManagement = Sidebar::where('name', 'System Management')->first();
        
        if ($systemManagement) {
            $roleManagement = Sidebar::create([
                'name' => 'Role Management',
                'route_name' => 'root.roles.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'order' => 3,
                'is_active' => true,
                'parent_id' => $systemManagement->id
            ]);

            // Assign to Root Role Template
            $rootRole = Role::where('name', 'root')->first();
            if ($rootRole) {
                RoleSidebar::firstOrCreate([
                    'role_id' => $rootRole->id,
                    'sidebar_id' => $roleManagement->id
                ]);

                // Also assign to existing root users
                $rootRoleUsers = RoleUser::where('role_id', $rootRole->id)->get();
                foreach ($rootRoleUsers as $ru) {
                    UserRoleSidebar::firstOrCreate([
                        'role_user_id' => $ru->id,
                        'sidebar_id' => $roleManagement->id
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sidebar = Sidebar::where('route_name', 'root.roles.index')->first();
        if ($sidebar) {
            $sidebar->delete();
        }
    }
};
