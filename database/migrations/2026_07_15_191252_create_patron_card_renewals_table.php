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
        Schema::create('patron_card_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->constrained('patron_details')->onDelete('cascade');
            $table->dateTime('old_expiry_date')->nullable();
            $table->dateTime('new_expiry_date')->nullable();
            $table->decimal('renew_fee', 15, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('patron_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_card_renewals');
    }
};
