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
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bibliographic_record_id')->constrained('bibliographic_records')->onDelete('cascade');
            $table->string('barcode')->unique();
            $table->string('accession_number')->unique();
            $table->string('storage_type')->nullable(); // Thể loại lưu trữ
            $table->integer('quantity')->default(1);
            $table->string('location')->nullable(); // Vị trí
            $table->string('temporary_location')->nullable(); // Vị trí tạm thời
            $table->string('status')->default('available'); // Trạng thái
            $table->string('order_code')->nullable(); // Mã đơn hàng
            $table->boolean('waits_for_print')->default(false); // Chờ in
            $table->text('notes')->nullable(); // Ghi chú
            $table->string('volume_issue')->nullable(); // Quyển, Số
            $table->integer('day')->nullable(); // Ngày
            $table->string('month_season')->nullable(); // Tháng, mùa
            $table->integer('year')->nullable(); // Năm
            $table->string('shelf')->nullable(); // Giá/kệ
            $table->string('shelf_position')->nullable(); // Vị trí xếp giá
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_items');
    }
};
