<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sidebar;
use App\Models\Role;
use App\Models\RoleSidebar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $systemManagement = Sidebar::where('name', 'System Management')->first();
        
        if (!$systemManagement) {
            $systemManagement = Sidebar::create([
                'name' => 'System Management',
                'route_name' => '#',
                'icon' => 'cog',
                'order' => 10,
                'is_active' => true,
            ]);
        }

        $rootRole = Role::where('name', 'root')->first();

        $tabs = [
            [
                'name' => 'User Privilege Management',
                'route_name' => 'admin.users.privileges',
                'icon' => '<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'order' => 3,
            ],
            [
                'name' => 'Role Management',
                'route_name' => 'admin.roles.index',
                'icon' => '<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
                'order' => 4,
            ],
            [
                'name' => 'Patron Categories',
                'route_name' => 'admin.patrons.groups.index',
                'icon' => '<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'order' => 5,
            ],
            [
                'name' => 'System Logs',
                'route_name' => 'admin.activity-logs.index',
                'icon' => '<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'order' => 10,
            ],
        ];

        $adminRole = Role::where('id', 1)->first() ?? Role::where('name', 'admin')->first();

        foreach ($tabs as $tab) {
            $sidebar = Sidebar::create(array_merge($tab, [
                'parent_id' => $systemManagement->id,
                'is_active' => true,
            ]));

            if ($rootRole) {
                RoleSidebar::firstOrCreate([
                    'role_id' => $rootRole->id,
                    'sidebar_id' => $sidebar->id
                ]);
            }

            if ($adminRole) {
                RoleSidebar::firstOrCreate([
                    'role_id' => $adminRole->id,
                    'sidebar_id' => $sidebar->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $routeNames = [
            'admin.users.privileges',
            'admin.roles.index',
            'admin.patrons.groups.index',
            'admin.activity-logs.index',
        ];

        Sidebar::whereIn('route_name', $routeNames)->delete();
    }
};
