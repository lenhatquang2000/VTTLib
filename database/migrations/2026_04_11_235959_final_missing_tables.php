<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. News Categories
        if (!Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('language', 5)->default('vi');
                $table->timestamps();
            });
        }

        // 2. News Tags
        if (!Schema::hasTable('news_tags')) {
            Schema::create('news_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        // 3. News
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('content')->nullable();
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->integer('view_count')->default(0);
                $table->string('language', 5)->default('vi');
                $table->timestamps();
            });
        }

        // 4. Pivot
        if (!Schema::hasTable('news_tag')) {
            Schema::create('news_tag', function (Blueprint $table) {
                $table->bigInteger('news_id')->unsigned();
                $table->bigInteger('tag_id')->unsigned();
            });
        }

        // 5. Media Items (Since Categories was created in final_tables)
        if (!Schema::hasTable('media_items')) {
            Schema::create('media_items', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('category_id')->unsigned();
                $table->string('title')->nullable();
                $table->string('image_url', 500);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // No rollback to be safe
    }
};
