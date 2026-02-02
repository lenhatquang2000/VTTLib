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
        Schema::table('book_items', function (Blueprint $table) {
            if (!Schema::hasColumn('book_items', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('bibliographic_record_id')->constrained('branches');
            }
            if (!Schema::hasColumn('book_items', 'storage_location_id')) {
                $table->foreignId('storage_location_id')->nullable()->after('branch_id')->constrained('storage_locations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_items', function (Blueprint $table) {
            if (Schema::hasColumn('book_items', 'branch_id')) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn(['branch_id']);
            }
            if (Schema::hasColumn('book_items', 'storage_location_id')) {
                $table->dropForeign(['storage_location_id']);
                $table->dropColumn(['storage_location_id']);
            }
        });
    }
};
