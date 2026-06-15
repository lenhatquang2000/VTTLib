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
        if (!Schema::hasTable('library_network_logos')) {
            Schema::create('library_network_logos', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Tên thư viện hoặc nhãn hiệu');
                $table->string('logo_path')->comment('Đường dẫn file logo');
                $table->string('url')->comment('URL đường dẫn của thư viện');
                $table->integer('sort_order')->default(0)->comment('Thứ tự sắp xếp');
                $table->boolean('is_active')->default(1)->comment('Trạng thái hoạt động');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_network_logos');
    }
};
