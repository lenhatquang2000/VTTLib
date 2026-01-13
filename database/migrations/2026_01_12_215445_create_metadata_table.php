<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->string('metadata_code')->unique();
            $table->string('metadata_name');
            $table->text('description')->nullable();
            $table->boolean('allow_multiple')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
