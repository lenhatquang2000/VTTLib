<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_metadata_value', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('metadata_value_id')->constrained('metadata_values')->onDelete('cascade');
            $table->primary(['book_id', 'metadata_value_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_metadata_value');
    }
};
