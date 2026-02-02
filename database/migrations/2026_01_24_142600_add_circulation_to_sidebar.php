<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Circulation parent menu
        $circulationId = DB::table('sidebars')->insertGetId([
            'name' => 'Circulation',
            'route_name' => null,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
            'order' => 35,
            'is_active' => true,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Circulation sub-menus
        $circulationMenus = [
            [
                'name' => 'Policies',
                'route_name' => 'admin.circulation.index',
                'icon' => null,
                'order' => 1,
                'parent_id' => $circulationId,
            ],
            [
                'name' => 'Loan Desk',
                'route_name' => 'admin.circulation.loan-desk',
                'icon' => null,
                'order' => 2,
                'parent_id' => $circulationId,
            ],
            [
                'name' => 'Fines',
                'route_name' => 'admin.circulation.fines',
                'icon' => null,
                'order' => 3,
                'parent_id' => $circulationId,
            ],
        ];

        foreach ($circulationMenus as $menu) {
            DB::table('sidebars')->insert([
                'name' => $menu['name'],
                'route_name' => $menu['route_name'],
                'icon' => $menu['icon'],
                'order' => $menu['order'],
                'is_active' => true,
                'parent_id' => $menu['parent_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Grant access to root role
        $rootRoleUserId = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'root')
            ->value('role_user.id');

        if ($rootRoleUserId) {
            $sidebarIds = DB::table('sidebars')
                ->where('parent_id', $circulationId)
                ->orWhere('id', $circulationId)
                ->pluck('id');

            foreach ($sidebarIds as $sidebarId) {
                DB::table('user_role_sidebars')->insertOrIgnore([
                    'role_user_id' => $rootRoleUserId,
                    'sidebar_id' => $sidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Grant access to admin role
        $adminRoleUserId = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'admin')
            ->value('role_user.id');

        if ($adminRoleUserId) {
            $sidebarIds = DB::table('sidebars')
                ->where('parent_id', $circulationId)
                ->orWhere('id', $circulationId)
                ->pluck('id');

            foreach ($sidebarIds as $sidebarId) {
                DB::table('user_role_sidebars')->insertOrIgnore([
                    'role_user_id' => $adminRoleUserId,
                    'sidebar_id' => $sidebarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get circulation sidebar id
        $circulationId = DB::table('sidebars')->where('name', 'Circulation')->value('id');
        
        if ($circulationId) {
            // Delete user_role_sidebars entries
            $sidebarIds = DB::table('sidebars')
                ->where('parent_id', $circulationId)
                ->orWhere('id', $circulationId)
                ->pluck('id');

            DB::table('user_role_sidebars')->whereIn('sidebar_id', $sidebarIds)->delete();
            
            // Delete sidebars
            DB::table('sidebars')->where('parent_id', $circulationId)->delete();
            DB::table('sidebars')->where('id', $circulationId)->delete();
        }
    }
};
