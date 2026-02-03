<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Sidebar;
use App\Models\RoleSidebar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $root = Role::where('name', 'root')->first();
        $admin = Role::where('name', 'admin')->first();
        $sidebars = Sidebar::all();

        if ($root) {
            foreach ($sidebars as $sidebar) {
                RoleSidebar::firstOrCreate([
                    'role_id' => $root->id,
                    'sidebar_id' => $sidebar->id
                ]);
            }
        }

        if ($admin) {
            foreach ($sidebars as $sidebar) {
                // Admin doesn't get Root-only paths
                if (strpos($sidebar->route_name, 'root.') === false) {
                    RoleSidebar::firstOrCreate([
                        'role_id' => $admin->id,
                        'sidebar_id' => $sidebar->id
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
        RoleSidebar::truncate();
    }
};
