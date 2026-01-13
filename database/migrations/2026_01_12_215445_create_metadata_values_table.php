<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadata_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metadata_id')->constrained('metadata')->onDelete('cascade');
            $table->string('value_code');
            $table->string('value_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata_values');
    }
};
