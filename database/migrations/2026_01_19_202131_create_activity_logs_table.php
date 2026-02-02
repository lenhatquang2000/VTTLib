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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who did it
            $table->string('action'); // e.g., 'patron_created', 'card_locked', 'card_renewed'
            $table->string('model_type')->nullable(); // Target model (e.g., App\Models\PatronDetail)
            $table->unsignedBigInteger('model_id')->nullable(); // Target ID
            $table->json('details')->nullable(); // Changes or meta data
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
