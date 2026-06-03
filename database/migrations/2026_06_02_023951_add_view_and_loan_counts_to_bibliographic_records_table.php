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
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->bigInteger('view_count')->default(0)->after('cover_image');
            $table->bigInteger('loan_count')->default(0)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->dropColumn(['view_count', 'loan_count']);
        });
    }
};
