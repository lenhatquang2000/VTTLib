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
        // Table for General System/Library Settings
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, library, email, etc.
            $table->timestamps();
        });

        // Table for Barcode Generation Rules
        Schema::create('barcode_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Sách Tiếng Việt", "Tài liệu tham khảo"
            $table->string('prefix')->nullable();
            $table->integer('length')->default(6);
            $table->bigInteger('start_number')->default(1);
            $table->bigInteger('current_number')->default(0);
            $table->boolean('is_active')->default(false);
            $table->string('target_type')->default('item'); // item, patron, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode_configs');
        Schema::dropIfExists('system_settings');
    }
};
