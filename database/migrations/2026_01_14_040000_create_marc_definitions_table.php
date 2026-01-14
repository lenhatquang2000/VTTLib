<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marc_tag_definitions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('tag', 3)->unique(); // 082, 100
            $blueprint->string('label'); // Tên hiển thị: "Mã ngôn ngữ"
            $blueprint->text('description')->nullable();
            $blueprint->timestamps();
        });

        Schema::create('marc_subfield_definitions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('tag', 3); // FK logic tới tag
            $blueprint->char('code', 1); // a, b, c
            $blueprint->string('label'); // "Mã ngôn ngữ văn bản"
            $blueprint->text('help_text')->nullable();
            $blueprint->timestamps();

            $blueprint->unique(['tag', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marc_subfield_definitions');
        Schema::dropIfExists('marc_tag_definitions');
    }
};
