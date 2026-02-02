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
        // 1. Patron Groups (Nhóm độc giả)
        Schema::create('patron_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sinh viên, Giảng viên, Khách
            $table->string('code')->unique(); // SV, GV, KHACH
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Circulation Policies (Chính sách lưu thông)
        Schema::create('circulation_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên chính sách
            $table->foreignId('patron_group_id')->constrained()->onDelete('cascade');
            
            // Loan settings
            $table->integer('max_loan_days')->default(14); // Số ngày mượn tối đa
            $table->integer('max_items')->default(5); // Số sách mượn tối đa
            $table->integer('max_renewals')->default(2); // Số lần gia hạn tối đa
            $table->integer('renewal_days')->default(7); // Số ngày mỗi lần gia hạn
            
            // Fine settings
            $table->decimal('fine_per_day', 10, 2)->default(1000); // Phí phạt/ngày (VND)
            $table->decimal('max_fine', 10, 2)->default(100000); // Phạt tối đa
            $table->integer('grace_period_days')->default(0); // Số ngày ân hạn
            
            // Reservation settings
            $table->boolean('can_reserve')->default(true); // Có thể đặt trước
            $table->integer('max_reservations')->default(3); // Số đặt trước tối đa
            $table->integer('reservation_hold_days')->default(3); // Số ngày giữ sách đặt trước
            
            // Restrictions
            $table->decimal('max_outstanding_fine', 10, 2)->default(50000); // Nợ tối đa được phép mượn
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Loan Transactions (Giao dịch mượn/trả)
        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('circulation_policy_id')->nullable()->constrained()->nullOnDelete();
            
            // Loan info
            $table->dateTime('loan_date');
            $table->dateTime('due_date');
            $table->dateTime('return_date')->nullable();
            
            // Renewal tracking
            $table->integer('renewal_count')->default(0);
            $table->dateTime('last_renewal_date')->nullable();
            
            // Status: borrowed, returned, overdue, lost
            $table->string('status')->default('borrowed');
            
            // Staff tracking
            $table->foreignId('loaned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('returned_to')->nullable()->constrained('users')->nullOnDelete();
            
            // Branch info
            $table->foreignId('loan_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('return_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patron_detail_id', 'status']);
            $table->index(['book_item_id', 'status']);
            $table->index('due_date');
        });

        // 4. Fines (Phạt)
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_transaction_id')->nullable()->constrained()->nullOnDelete();
            
            // Fine details
            $table->string('fine_type'); // overdue, lost, damaged
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('waived_amount', 10, 2)->default(0);
            
            // Status: pending, partial, paid, waived
            $table->string('status')->default('pending');
            
            // Payment info
            $table->dateTime('paid_date')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payment_method')->nullable(); // cash, transfer, card
            
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patron_detail_id', 'status']);
        });

        // 5. Reservations (Đặt trước)
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
            $table->foreignId('bibliographic_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_item_id')->nullable()->constrained()->nullOnDelete(); // Assigned when available
            
            // Reservation info
            $table->dateTime('reservation_date');
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('pickup_date')->nullable();
            
            // Status: pending, ready, fulfilled, cancelled, expired
            $table->string('status')->default('pending');
            
            // Branch for pickup
            $table->foreignId('pickup_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            
            // Notification
            $table->boolean('notified')->default(false);
            $table->dateTime('notified_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patron_detail_id', 'status']);
            $table->index(['bibliographic_record_id', 'status']);
        });

        // Add patron_group_id to patron_details
        Schema::table('patron_details', function (Blueprint $table) {
            $table->foreignId('patron_group_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patron_details', function (Blueprint $table) {
            $table->dropForeign(['patron_group_id']);
            $table->dropColumn('patron_group_id');
        });
        
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('loan_transactions');
        Schema::dropIfExists('circulation_policies');
        Schema::dropIfExists('patron_groups');
    }
};
