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
        Schema::table('digital_resources', function (Blueprint $table) {
            $table->integer('pages')->nullable()->after('format')->comment('Số trang của tài liệu số');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_resources', function (Blueprint $table) {
            $table->dropColumn('pages');
        });
    }
};
