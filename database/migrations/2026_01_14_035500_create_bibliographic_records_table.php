<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bibliographic_records', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('leader', 24)->nullable();
            $blueprint->string('record_type')->default('book'); // book, thesis, etc.
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bibliographic_records');
    }
};
