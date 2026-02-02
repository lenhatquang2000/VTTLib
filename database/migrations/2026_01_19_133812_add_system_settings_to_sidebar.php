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
        $systemManagement = \App\Models\Sidebar::create([
            'name' => 'System Management',
            'route_name' => '#',
            'icon' => 'cog',
            'order' => 10,
            'is_active' => true,
        ]);

        \App\Models\Sidebar::create([
            'name' => 'System Settings',
            'route_name' => 'admin.settings.index',
            'icon' => 'adjustments',
            'order' => 1,
            'is_active' => true,
            'parent_id' => $systemManagement->id
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sm = \App\Models\Sidebar::where('name', 'System Management')->first();
        if ($sm) {
            \App\Models\Sidebar::where('parent_id', $sm->id)->delete();
            $sm->delete();
        }
    }
};
