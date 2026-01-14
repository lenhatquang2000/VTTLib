<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marc_subfields', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('marc_field_id')->constrained('marc_fields')->onDelete('cascade');
            $blueprint->char('code', 1); // a, b, c, etc.
            $blueprint->text('value')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marc_subfields');
    }
};
