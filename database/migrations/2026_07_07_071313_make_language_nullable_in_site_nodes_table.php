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
        Schema::table('site_nodes', function (Blueprint $table) {
            $table->string('language', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_nodes', function (Blueprint $table) {
            $table->string('language', 5)->default('vi')->nullable(false)->change();
        });
    }
};
