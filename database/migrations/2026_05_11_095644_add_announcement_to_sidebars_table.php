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
        \DB::table('sidebars')->insert([
            'name' => 'Announcements',
            'name_vi' => 'Thông báo',
            'name_en' => 'Announcements',
            'route_name' => 'admin.news.announcements', // Giả sử route này tồn tại hoặc sẽ thêm
            'icon' => '<i class="fas fa-bullhorn"></i>',
            'order' => 15,
            'is_active' => 1,
            'parent_id' => 45,
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
