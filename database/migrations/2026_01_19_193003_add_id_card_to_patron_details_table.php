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
        Schema::table('patron_details', function (Blueprint $table) {
            $table->string('id_card')->nullable()->after('patron_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patron_details', function (Blueprint $table) {
            $table->dropColumn('id_card');
        });
    }
};
