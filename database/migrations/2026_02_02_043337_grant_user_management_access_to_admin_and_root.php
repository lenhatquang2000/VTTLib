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
        $roles = Role::whereIn('name', ['root', 'admin'])->get();
        if ($roles->isEmpty()) return;

        $userManagementSidebar = Sidebar::where('name', 'User Management')->first();
        if (!$userManagementSidebar) return;

        foreach ($roles as $role) {
            $roleUsers = RoleUser::where('role_id', $role->id)->get();

            foreach ($roleUsers as $roleUser) {
                UserRoleSidebar::firstOrCreate([
                    'role_user_id' => $roleUser->id,
                    'sidebar_id' => $userManagementSidebar->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $userManagementSidebar = Sidebar::where('name', 'User Management')->first();
        if ($userManagementSidebar) {
            UserRoleSidebar::where('sidebar_id', $userManagementSidebar->id)->delete();
        }
    }
};
