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
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                
                // Fields from Banner.php model
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('image_url')->nullable();
                $table->string('link_url')->nullable();
                $table->string('link_target')->default('_self');
                $table->string('position')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status')->default('active');
                $table->dateTime('start_date')->nullable();
                $table->dateTime('end_date')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->string('language', 5)->default('vi');
                $table->integer('click_count')->default(0);
                $table->integer('view_count')->default(0);
                $table->json('settings')->nullable();
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
