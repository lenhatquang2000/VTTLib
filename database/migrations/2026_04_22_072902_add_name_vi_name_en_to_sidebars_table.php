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
        Schema::table('sidebars', function (Blueprint $table) {
            $table->string('name_vi')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_vi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidebars', function (Blueprint $table) {
            $table->dropColumn(['name_vi', 'name_en']);
        });
    }
};
