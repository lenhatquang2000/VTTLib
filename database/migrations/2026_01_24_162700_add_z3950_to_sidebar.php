<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find Cataloging parent menu
        $catalogingId = DB::table('sidebars')->where('name', 'Cataloging')->value('id');
        
        if (!$catalogingId) {
            $catalogingId = DB::table('sidebars')->insertGetId([
                'name' => 'Cataloging',
                'route_name' => null,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'order' => 20,
                'is_active' => true,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add Z39.50 menu
        $z3950Id = DB::table('sidebars')->insertGetId([
            'name' => 'Z39.50 Servers',
            'route_name' => 'admin.z3950.index',
            'icon' => null,
            'order' => 10,
            'is_active' => true,
            'parent_id' => $catalogingId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Grant access to root and admin roles
        $roleUserIds = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereIn('roles.name', ['root', 'admin'])
            ->pluck('role_user.id');

        foreach ($roleUserIds as $roleUserId) {
            DB::table('user_role_sidebars')->insertOrIgnore([
                'role_user_id' => $roleUserId,
                'sidebar_id' => $z3950Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $sidebarId = DB::table('sidebars')->where('route_name', 'admin.z3950.index')->value('id');
        
        if ($sidebarId) {
            DB::table('user_role_sidebars')->where('sidebar_id', $sidebarId)->delete();
            DB::table('sidebars')->where('id', $sidebarId)->delete();
        }
    }
};
