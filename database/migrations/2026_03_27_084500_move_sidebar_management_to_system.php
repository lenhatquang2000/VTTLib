<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Sidebar Management to be child of System Management
        DB::table('sidebars')
            ->where('name', 'Sidebar Management')
            ->update([
                'parent_id' => 13, // System Management ID
                'order' => 4, // After System Settings
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert Sidebar Management to top-level
        DB::table('sidebars')
            ->where('name', 'Sidebar Management')
            ->update([
                'parent_id' => null,
                'order' => 99,
            ]);
    }
};
