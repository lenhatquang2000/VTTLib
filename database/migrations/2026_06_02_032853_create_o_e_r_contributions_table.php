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
        if (!Schema::hasTable('oer_contributions')) {
            Schema::create('oer_contributions', function (Blueprint $table) {
                $table->id();
                $table->string('full_name');
                $table->string('contact_info');
                $table->string('license');
                $table->text('additional_info')->nullable();
                $table->string('file_path');
                $table->string('file_name');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oer_contributions');
    }
};
