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
        // 1. News Categories
        if (!Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->text('description')->nullable();
                $table->string('color', 7)->nullable();
                $table->string('icon', 50)->nullable();
                $table->foreignId('parent_id')->nullable()->constrained('news_categories')->onDelete('cascade');
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
                $table->string('name', 50);
                $table->string('slug', 50)->unique();
                $table->string('language', 5)->default('vi');
                $table->timestamps();
            });
        }

        // 3. News
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('summary')->nullable();
                $table->longText('content')->nullable();
                $table->string('featured_image')->nullable();
                $table->foreignId('category_id')->nullable()->constrained('news_categories')->onDelete('set null');
                $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->integer('view_count')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->string('language', 5)->default('vi');
                $table->timestamps();
            });
        }

        // 4. News-Tag Pivot
        if (!Schema::hasTable('news_tag')) {
            Schema::create('news_tag', function (Blueprint $table) {
                $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('news_tags')->onDelete('cascade');
                $table->primary(['news_id', 'tag_id']);
            });
        }

        // 5. Media Categories
        if (!Schema::hasTable('media_categories')) {
            Schema::create('media_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('code', 50)->unique();
                $table->string('position', 50)->nullable();
                $table->enum('type', ['slider', 'banner', 'logo', 'gallery', 'other'])->default('other');
                $table->boolean('is_active')->default(true);
                $table->string('language', 5)->default('vi');
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }

        // 6. Media Items
        if (!Schema::hasTable('media_items')) {
            Schema::create('media_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('media_categories')->onDelete('cascade');
                $table->string('title', 255)->nullable();
                $table->string('image_url', 500);
                $table->string('link_url', 500)->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_final_tables');
    }
};
