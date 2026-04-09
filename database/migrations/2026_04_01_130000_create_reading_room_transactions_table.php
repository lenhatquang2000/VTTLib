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
        Schema::create('reading_room_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_item_id')->constrained()->onDelete('cascade');
            
            // Transaction info
            $table->dateTime('checkout_time');
            $table->dateTime('checkin_time')->nullable();
            $table->dateTime('due_time'); // Must return by end of day
            
            // Status: active, returned, overdue
            $table->string('status')->default('active');
            
            // Staff info
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('checkout_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            
            // Notes
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patron_detail_id', 'status']);
            $table->index(['book_item_id', 'status']);
            $table->index(['checkout_time']);
            $table->index(['due_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_room_transactions');
    }
};
