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
            $table->string('framework')->default('AVMARC21')->after('leader');
            $table->string('subject_category')->nullable()->after('framework');
            $table->string('serial_frequency')->nullable()->after('subject_category');
            $table->string('date_type')->nullable()->after('serial_frequency');
            $table->string('acquisition_method')->nullable()->after('date_type');
            $table->string('document_format')->nullable()->after('acquisition_method');
            $table->string('cataloging_standard')->nullable()->after('document_format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->dropColumn([
                'framework',
                'subject_category',
                'serial_frequency',
                'date_type',
                'acquisition_method',
                'document_format',
                'cataloging_standard'
            ]);
        });
    }
};
