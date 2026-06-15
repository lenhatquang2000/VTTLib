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
        // Check if parent exists, if not insert without parent
        $parentExists = \DB::table('sidebars')->where('id', 45)->exists();
        
        \DB::table('sidebars')->insert([
            'name' => 'Announcements',
            'name_vi' => 'Thông báo',
            'name_en' => 'Announcements',
            'route_name' => 'admin.news.announcements',
            'icon' => '<i class="fas fa-bullhorn"></i>',
            'order' => 15,
            'is_active' => 1,
            'parent_id' => $parentExists ? 45 : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('sidebars')->where('name', 'Announcements')->where('parent_id', 45)->delete();
    }
};
