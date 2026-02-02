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
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('description');
            $table->string('status')->default('active')->after('job_title'); // active, inactive, suspended
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'description', 
                'job_title',
                'status',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};
