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
        Schema::create('site_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('node_code', 50)->unique(); // Mã node duy nhất
            $table->string('node_name', 100); // Tên node nội bộ
            $table->string('display_name', 100); // Tên hiển thị
            $table->text('description')->nullable(); // Mô tả
            $table->foreignId('parent_id')->nullable()->constrained('site_nodes')->onDelete('cascade'); // Node cha
            $table->string('icon', 100)->nullable(); // Biểu tượng
            $table->string('masterpage', 100)->nullable(); // Template sử dụng
            $table->string('display_type', 20)->default('menu'); // Kiểu hiển thị: menu, sidebar, footer
            $table->string('target', 10)->default('_self'); // _self, _blank
            $table->boolean('is_active')->default(true); // Trạng thái hiển thị
            $table->string('access_type', 20)->default('public'); // public, auth, roles
            $table->json('allowed_roles')->nullable(); // Roles được phép truy cập
            $table->boolean('allow_guest')->default(true); // Cho phép khách
            $table->longText('content')->nullable(); // Nội dung trang tĩnh
            $table->string('route_name', 100)->nullable(); // Route hệ thống
            $table->string('url', 255)->nullable(); // URL tùy chỉnh
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->string('language', 5)->default('vi'); // Ngôn ngữ
            $table->string('meta_title', 255)->nullable(); // SEO
            $table->text('meta_description')->nullable(); // SEO
            $table->string('meta_keywords', 255)->nullable(); // SEO
            $table->timestamps();

            // Indexes
            $table->index(['parent_id']);
            $table->index(['is_active']);
            $table->index(['language']);
            $table->index(['sort_order']);
            $table->index(['node_code']);
            $table->index(['access_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_nodes');
    }
};
