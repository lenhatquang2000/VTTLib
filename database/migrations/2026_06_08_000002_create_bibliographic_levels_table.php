<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bibliographic_levels')) {
            Schema::create('bibliographic_levels', function (Blueprint $table) {
                $table->id();
                $table->char('code', 1)->unique(); // a, c, d, e, f, g, i, j, k, m, o, p, r, t
                $table->string('name_en'); // English name
                $table->string('name_vi'); // Vietnamese name
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('order')->default(0);
                $table->timestamps();
                
                $table->index('is_active');
                $table->index('order');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bibliographic_levels');
    }
};
