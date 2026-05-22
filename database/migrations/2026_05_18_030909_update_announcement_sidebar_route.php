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
        \DB::table('sidebars')
            ->where('name', 'Announcements')
            ->update(['route_name' => 'admin.announcements.index']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('sidebars')
            ->where('name', 'Announcements')
            ->update(['route_name' => 'admin.news.announcements']);
    }
};
