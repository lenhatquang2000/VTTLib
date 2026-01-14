<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sidebar;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\UserRoleSidebar;

return new class extends Migration {
    public function up(): void
    {
        $tab = Sidebar::create([
            'name' => 'MARC Framework',
            'route_name' => 'admin.marc.index',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
            'order' => 6
        ]);

        $agent = User::where('email', 'agent@vttlib.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if ($agent && $adminRole) {
            $roleUser = RoleUser::where('user_id', $agent->id)->where('role_id', $adminRole->id)->first();
            if ($roleUser) {
                UserRoleSidebar::create([
                    'role_user_id' => $roleUser->id,
                    'sidebar_id' => $tab->id
                ]);
            }
        }
    }

    public function down(): void
    {
        $tab = Sidebar::where('route_name', 'admin.marc.index')->first();
        if ($tab) {
            UserRoleSidebar::where('sidebar_id', $tab->id)->delete();
            $tab->delete();
        }
    }
};
