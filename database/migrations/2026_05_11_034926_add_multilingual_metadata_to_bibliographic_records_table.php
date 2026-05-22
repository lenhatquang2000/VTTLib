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
            // Thêm các trường lưu tên hiển thị đa ngôn ngữ
            $table->string('status_vi')->nullable()->after('status');
            $table->string('status_en')->nullable()->after('status_vi');
            
            $table->string('record_type_vi')->nullable()->after('record_type');
            $table->string('record_type_en')->nullable()->after('record_type_vi');
            
            $table->string('bibliographic_level')->nullable()->after('record_type_en');
            $table->string('bibliographic_level_vi')->nullable()->after('bibliographic_level');
            $table->string('bibliographic_level_en')->nullable()->after('bibliographic_level_vi');
            
            $table->string('serial_frequency_vi')->nullable()->after('serial_frequency');
            $table->string('serial_frequency_en')->nullable()->after('serial_frequency_vi');
            
            $table->string('date_type_vi')->nullable()->after('date_type');
            $table->string('date_type_en')->nullable()->after('date_type_vi');
            
            $table->string('acquisition_method_vi')->nullable()->after('acquisition_method');
            $table->string('acquisition_method_en')->nullable()->after('acquisition_method_vi');
            
            $table->string('document_format_vi')->nullable()->after('document_format');
            $table->string('document_format_en')->nullable()->after('document_format_vi');
            
            $table->string('cataloging_standard_vi')->nullable()->after('cataloging_standard');
            $table->string('cataloging_standard_en')->nullable()->after('cataloging_standard_vi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->dropColumn([
                'status_vi', 'status_en',
                'record_type_vi', 'record_type_en',
                'bibliographic_level', 'bibliographic_level_vi', 'bibliographic_level_en',
                'serial_frequency_vi', 'serial_frequency_en',
                'date_type_vi', 'date_type_en',
                'acquisition_method_vi', 'acquisition_method_en',
                'document_format_vi', 'document_format_en',
                'cataloging_standard_vi', 'cataloging_standard_en'
            ]);
        });
    }
};
