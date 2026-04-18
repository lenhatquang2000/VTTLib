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
        Schema::create('site_node_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_node_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // hero, features, text, image, button, gallery, etc.
            $table->json('item_data'); // content, image_url, text, button_text, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['site_node_id', 'sort_order']);
            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_node_items');
    }
};
