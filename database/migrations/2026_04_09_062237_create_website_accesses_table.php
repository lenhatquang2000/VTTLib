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
        Schema::create('website_accesses', function (Blueprint $table) {
            $table->id();
            $table->dateTime('access_time');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('access_type', 20)->default('visit'); // visit, search, download, login
            $table->string('device_type', 20)->nullable(); // desktop, mobile, tablet
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->text('referrer')->nullable();
            $table->timestamps();

            $table->index(['access_time']);
            $table->index(['user_id']);
            $table->index(['session_id']);
            $table->index(['access_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_accesses');
    }
};
