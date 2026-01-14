<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marc_fields', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('record_id')->constrained('bibliographic_records')->onDelete('cascade');
            $blueprint->string('tag', 3); // 082, 100, etc.
            $blueprint->char('indicator1', 1)->default(' ');
            $blueprint->char('indicator2', 1)->default(' ');
            $blueprint->integer('sequence')->default(0);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marc_fields');
    }
};
