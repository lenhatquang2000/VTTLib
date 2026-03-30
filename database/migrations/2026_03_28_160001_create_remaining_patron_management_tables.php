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
        // 1. Patron Lock History (Lược sử khóa độc giả)
        if (!Schema::hasTable('patron_lock_history')) {
            Schema::create('patron_lock_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
                $table->foreignId('locked_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('unlocked_by')->nullable()->constrained('users')->nullOnDelete();
                
                // Lock details
                $table->string('action'); // lock, unlock
                $table->text('reason')->nullable();
                $table->decimal('unlock_fee', 10, 2)->default(0);
                
                // Timestamps
                $table->dateTime('locked_at')->nullable();
                $table->dateTime('unlocked_at')->nullable();
                
                $table->timestamps();
                
                $table->index(['patron_detail_id', 'action']);
                $table->index('locked_at');
            });
        }

        // 2. Print Queue (Danh sách chờ in thẻ)
        if (!Schema::hasTable('print_queue')) {
            Schema::create('print_queue', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
                $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
                
                // Print details
                $table->string('status')->default('pending'); // pending, printed, cancelled
                $table->integer('priority')->default(0); // Higher number = higher priority
                $table->text('notes')->nullable();
                
                // Timestamps
                $table->dateTime('printed_at')->nullable();
                $table->foreignId('printed_by')->nullable()->constrained('users')->nullOnDelete();
                
                $table->timestamps();
                
                $table->index(['status', 'priority']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_queue');
        Schema::dropIfExists('patron_lock_history');
    }
};
