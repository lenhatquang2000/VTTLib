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
        Schema::table('export_histories', function (Blueprint $table) {
            $table->unsignedInteger('execution_time_ms')->nullable()->after('status')->comment('Thời gian thực thi xuất file tính bằng mili-giây');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('export_histories', function (Blueprint $table) {
            $table->dropColumn('execution_time_ms');
        });
    }
};
