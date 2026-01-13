<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_role_sidebars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_user_id')->constrained('role_user')->onDelete('cascade');
            $table->foreignId('sidebar_id')->constrained('sidebars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_role_sidebars');
    }
};
