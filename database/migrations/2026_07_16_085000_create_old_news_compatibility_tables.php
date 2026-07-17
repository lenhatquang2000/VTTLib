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
        // 1. news_article_types
        if (!Schema::hasTable('news_article_types')) {
            Schema::create('news_article_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('friendly_name')->nullable();
                $table->text('description')->nullable();
                $table->string('customer_id')->nullable();
                $table->timestamps();
            });
        }

        // 2. news_authors
        if (!Schema::hasTable('news_authors')) {
            Schema::create('news_authors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('customer_id')->nullable();
                $table->timestamps();
            });
        }

        // 3. Modify news_categories table
        Schema::table('news_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('news_categories', 'customer_id')) {
                $table->string('customer_id')->nullable()->after('language');
            }
        });

        // 4. Modify news table
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'article_type_id')) {
                $table->bigInteger('article_type_id')->unsigned()->nullable()->after('category_id');
                $table->foreign('article_type_id')->references('id')->on('news_article_types')->onDelete('set null');
            }
            if (!Schema::hasColumn('news', 'news_author_id')) {
                $table->bigInteger('news_author_id')->unsigned()->nullable()->after('author_id');
                $table->foreign('news_author_id')->references('id')->on('news_authors')->onDelete('set null');
            }
            if (!Schema::hasColumn('news', 'old_item_id')) {
                $table->bigInteger('old_item_id')->unsigned()->nullable()->after('news_author_id');
            }
            if (!Schema::hasColumn('news', 'customer_id')) {
                $table->string('customer_id')->nullable()->after('language');
            }
        });

        // 5. news_media
        if (!Schema::hasTable('news_media')) {
            Schema::create('news_media', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('news_id')->unsigned()->nullable();
                $table->string('media_name')->nullable();
                $table->string('media_extension', 50)->nullable();
                $table->integer('media_type')->nullable();
                $table->text('media_note')->nullable();
                $table->string('file_path', 500);
                $table->boolean('is_display')->default(true);
                $table->string('customer_id')->nullable();
                $table->timestamps();

                $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop news_media table
        Schema::dropIfExists('news_media');

        // 2. Modify news table
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'article_type_id')) {
                $table->dropForeign(['article_type_id']);
                $table->dropColumn('article_type_id');
            }
            if (Schema::hasColumn('news', 'news_author_id')) {
                $table->dropForeign(['news_author_id']);
                $table->dropColumn('news_author_id');
            }
            if (Schema::hasColumn('news', 'old_item_id')) {
                $table->dropColumn('old_item_id');
            }
            if (Schema::hasColumn('news', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
        });

        // 3. Modify news_categories table
        Schema::table('news_categories', function (Blueprint $table) {
            if (Schema::hasColumn('news_categories', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
        });

        // 4. Drop news_authors table
        Schema::dropIfExists('news_authors');

        // 5. Drop news_article_types table
        Schema::dropIfExists('news_article_types');
    }
};
