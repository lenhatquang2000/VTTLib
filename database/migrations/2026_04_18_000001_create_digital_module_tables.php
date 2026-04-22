<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng Thư mục tài liệu số
        Schema::create('digital_folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_code', 50)->unique(); // Mã thư mục
            $table->string('folder_name', 255);         // Tên thư mục
            $table->text('description')->nullable();    // Mô tả thư mục
            $table->foreignId('parent_id')->nullable()->constrained('digital_folders')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('language', 5)->default('vi');
            $table->timestamps();
        });

        // 2. Bảng Tài liệu số (Chứa 23 trường Metadata)
        Schema::create('digital_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('digital_folders')->onDelete('cascade');
            
            // Metadata bắt buộc (*)
            $table->string('title', 500);               // Tiêu đề (*)
            $table->string('resource_type', 100);       // Loại tài liệu (*)
            $table->string('file_path', 500);           // Đường dẫn file (*)
            $table->string('language', 50);             // Ngôn ngữ (*)

            // Metadata bổ sung (hỗ trợ nhiều giá trị bằng JSON cho các trường có dấu +)
            $table->json('authors')->nullable();        // Tác giả (+)
            $table->json('subjects')->nullable();       // Chủ đề / Môn học (+)
            $table->json('topics')->nullable();         // Đề mục (+)
            $table->json('secondary_authors')->nullable(); // Tác giả phụ (+)
            
            $table->text('description')->nullable();    // Mô tả
            $table->string('publisher', 255)->nullable(); // Nhà xuất bản
            $table->string('publish_year', 20)->nullable(); // Năm/Ngày xuất bản
            $table->string('format', 100)->nullable();  // Định dạng (PDF, MP4...)
            $table->string('identifier', 255)->nullable(); // Định danh tư liệu (ISBN, DOI...)
            $table->string('source', 500)->nullable();  // Nguồn gốc
            $table->string('link', 500)->nullable();    // Liên kết
            $table->string('coverage', 500)->nullable(); // Phạm vi
            $table->text('copyright')->nullable();      // Bản quyền
            $table->string('cataloging_link', 500)->nullable(); // Liên kết biên mục

            // Thông tin hệ thống
            $table->string('file_name', 255);           // Tên tập tin gốc
            $table->bigInteger('file_size')->default(0); // Dung lượng
            $table->enum('status', ['draft', 'published'])->default('draft'); // Trạng thái (Cập nhật / Ban hành)
            $table->bigInteger('view_count')->default(0); // Lượt xem
            $table->bigInteger('download_count')->default(0); // Lượt tải
            
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes(); // Xóa mềm theo quy định kiểm soát ràng buộc
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_resources');
        Schema::dropIfExists('digital_folders');
    }
};
