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
        DB::table('sidebars')->where('name', 'Books')->update([
            'route_name' => 'admin.marc.book'
        ]);
    }

    public function down(): void
    {
        DB::table('sidebars')->where('name', 'Books')->update([
            'route_name' => 'admin.books.index' // Assume this was the old one
        ]);
    }
};
