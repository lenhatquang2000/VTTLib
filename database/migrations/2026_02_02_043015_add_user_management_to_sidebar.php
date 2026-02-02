<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sidebar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $systemManagement = Sidebar::where('name', 'System Management')->first();
        
        if ($systemManagement) {
            Sidebar::create([
                'name' => 'User Management',
                'route_name' => 'admin.users.index',
                'icon' => 'users',
                'order' => 2,
                'is_active' => true,
                'parent_id' => $systemManagement->id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Sidebar::where('name', 'User Management')
            ->whereHas('parent', function($q) {
                $q->where('name', 'System Management');
            })
            ->delete();
    }
};
