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
        Schema::create('library_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patron_detail_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->string('purpose', 100)->nullable(); // Mượn sách, đọc tại chỗ, tra cứu, etc.
            $table->string('entry_type', 20)->default('physical'); // physical, digital
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['entry_time']);
            $table->index(['patron_detail_id']);
            $table->index(['branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_entries');
    }
};
