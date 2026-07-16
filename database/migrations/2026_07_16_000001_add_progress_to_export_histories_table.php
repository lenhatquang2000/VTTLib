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
            if (!Schema::hasColumn('export_histories', 'progress')) {
                $table->integer('progress')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('export_histories', function (Blueprint $table) {
            if (Schema::hasColumn('export_histories', 'progress')) {
                $table->dropColumn('progress');
            }
        });
    }
};
