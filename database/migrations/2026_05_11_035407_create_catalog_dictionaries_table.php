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
        Schema::create('catalog_dictionaries', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index()->comment('Loại danh mục: status, record_type, ...');
            $table->string('code')->index()->comment('Mã giá trị');
            $table->string('name_vi');
            $table->string('name_en');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_dictionaries');
    }
};
