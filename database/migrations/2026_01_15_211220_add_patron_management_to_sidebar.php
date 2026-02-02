<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Parent Tab
        $parent = \App\Models\Sidebar::create([
            'name' => 'Patron Management', // Will be translated via lang files
            'route_name' => '#',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'order' => 6,
        ]);

        // 2. Create Children
        $child1 = \App\Models\Sidebar::create([
            'parent_id' => $parent->id,
            'name' => 'Patron List',
            'route_name' => 'admin.patrons.index',
            'order' => 1,
        ]);

        $child2 = \App\Models\Sidebar::create([
            'parent_id' => $parent->id,
            'name' => 'Add New Patron',
            'route_name' => 'admin.patrons.create',
            'order' => 2,
        ]);

        // 3. Assign to Admin/Root roles
        $adminRoles = \App\Models\Role::whereIn('name', ['admin', 'root'])->pluck('id');
        $roleUsers = \App\Models\RoleUser::whereIn('role_id', $adminRoles)->get();

        foreach ($roleUsers as $ru) {
            foreach ([$parent->id, $child1->id, $child2->id] as $sidebarId) {
                \App\Models\UserRoleSidebar::updateOrCreate([
                    'role_user_id' => $ru->id,
                    'sidebar_id' => $sidebarId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $parent = \App\Models\Sidebar::where('name', 'Patron Management')->first();
        if ($parent) {
            \App\Models\Sidebar::where('parent_id', $parent->id)->delete();
            $parent->delete();
        }
    }
};
