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
        Schema::create('oer_resources', function (Blueprint $table) {
            $table->id();
            
            // Metadata cơ bản
            $table->string('title', 500);
            $table->string('resource_type', 100); // video, course, textbook, simulation, etc.
            $table->string('file_path', 500)->nullable();
            $table->string('language', 50)->default('vi');
            
            // Metadata OER đặc thù
            $table->json('authors')->nullable();
            $table->json('subjects')->nullable();
            $table->json('educational_levels')->nullable(); // Cấp độ giáo dục
            $table->string('license', 100)->nullable(); // CC BY, CC BY-SA, etc.
            $table->string('license_url', 500)->nullable();
            
            // Metadata bổ sung
            $table->text('description')->nullable();
            $table->string('publisher', 255)->nullable();
            $table->string('publish_year', 20)->nullable();
            $table->string('format', 100)->nullable();
            $table->string('identifier', 255)->nullable();
            $table->string('source', 500)->nullable();
            $table->string('external_link', 500)->nullable();
            $table->text('keywords')->nullable();
            
            // Thông tin hệ thống
            $table->string('file_name', 255)->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->string('cover_path', 500)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->bigInteger('view_count')->default(0);
            $table->bigInteger('download_count')->default(0);
            
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oer_resources');
    }
};
