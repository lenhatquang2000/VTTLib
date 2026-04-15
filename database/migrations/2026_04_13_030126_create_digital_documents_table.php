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
        if (!Schema::hasTable('digital_categories')) {
            Schema::create('digital_categories', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('name', 255);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('digital_documents')) {
            Schema::create('digital_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('folder_id')
                    ->constrained('digital_categories')
                    ->restrictOnDelete();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('file_url')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_documents');
    }
};
