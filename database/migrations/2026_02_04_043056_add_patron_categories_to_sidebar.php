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
        $usersParent = \App\Models\Sidebar::where('name', 'Users')->first();
        
        if ($usersParent) {
            $sidebar = \App\Models\Sidebar::create([
                'parent_id' => $usersParent->id,
                'name' => 'Patron Categories',
                'route_name' => 'root.patrons.groups.index',
                'icon' => '',
                'order' => 10,
                'is_active' => true
            ]);

            // Grant access to Root and Admin roles
            $roles = \App\Models\Role::whereIn('name', ['root', 'admin'])->get();
            foreach ($roles as $role) {
                // Find associated role_user entries
                $roleUsers = \Illuminate\Support\Facades\DB::table('role_user')
                    ->where('role_id', $role->id)
                    ->get();
                
                foreach ($roleUsers as $ru) {
                    \Illuminate\Support\Facades\DB::table('user_role_sidebars')->insert([
                        'role_user_id' => $ru->id,
                        'sidebar_id' => $sidebar->id,
                        'created_at' => now(),
                        'updated_at' => now(),
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
        $sidebar = \App\Models\Sidebar::where('route_name', 'root.patrons.groups.index')->first();
        if ($sidebar) {
            \Illuminate\Support\Facades\DB::table('user_role_sidebars')->where('sidebar_id', $sidebar->id)->delete();
            $sidebar->delete();
        }
    }
};
