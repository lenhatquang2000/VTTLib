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
        // 1. Table for Patron Details (linked 1:1 with users)
        Schema::create('patron_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Identification
            $table->string('patron_code')->unique(); // Mã độc giả
            $table->string('mssv')->nullable()->unique(); // MSSV
            $table->string('phone_contact')->nullable(); // Số danh bạ
            $table->string('display_name')->nullable(); // Tên hiển thị
            
            // Status
            $table->string('card_status')->default('normal'); // Bình thường, Khóa, v.v.
            $table->boolean('is_read_only')->default(false); // Chỉ đăng ký đọc
            $table->boolean('is_waiting_for_print')->default(false); // Thẻ chờ in
            
            // Personal Info
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('profile_image')->nullable();
            
            // Organization
            $table->string('school_name')->nullable();
            $table->string('batch')->nullable(); // Khóa
            $table->string('department')->nullable(); // Bộ phận
            $table->string('position_class')->nullable(); // Chức vụ/Lớp
            
            // Contact
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('branch')->default('all'); // Chi nhánh
            $table->string('classification')->default('individual'); // Phân loại (Cá nhân, v.v.)
            
            // Financials
            $table->decimal('card_fee', 15, 2)->default(0);
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            
            // System Dates
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('creator_id')->nullable()->constrained('users');
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Table for multiple addresses
        Schema::create('patron_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained('patron_details')->onDelete('cascade');
            $table->string('address_line');
            $table->string('type')->default('home'); // home, work, v.v.
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_addresses');
        Schema::dropIfExists('patron_details');
    }
};
