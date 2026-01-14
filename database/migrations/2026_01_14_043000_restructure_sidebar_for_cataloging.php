<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sidebar;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\UserRoleSidebar;

return new class extends Migration {
    public function up(): void
    {
        // 1. Create the parent "Cataloging" tab
        $cataloging = Sidebar::create([
            'name' => 'Cataloging',
            'route_name' => '#',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
            'order' => 3
        ]);

        // 2. Find Books and MARC tabs to move them under Cataloging
        $booksTab = Sidebar::where('name', 'Books')->first();
        $marcTab = Sidebar::where('name', 'MARC Framework')->first();

        if ($booksTab) {
            $booksTab->update(['parent_id' => $cataloging->id, 'order' => 1]);
        }
        if ($marcTab) {
            $marcTab->update(['parent_id' => $cataloging->id, 'order' => 2]);
        }

        // 3. Assign the parent tab to relevant users who have access to children
        // For the agent user
        $agent = User::where('email', 'agent@vttlib.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if ($agent && $adminRole) {
            $roleUser = RoleUser::where('user_id', $agent->id)->where('role_id', $adminRole->id)->first();
            if ($roleUser) {
                // Check if already assigned (unlikely but safe)
                if (!UserRoleSidebar::where('role_user_id', $roleUser->id)->where('sidebar_id', $cataloging->id)->exists()) {
                    UserRoleSidebar::create([
                        'role_user_id' => $roleUser->id,
                        'sidebar_id' => $cataloging->id
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $cataloging = Sidebar::where('name', 'Cataloging')->first();
        if ($cataloging) {
            // Restore children to root
            Sidebar::where('parent_id', $cataloging->id)->update(['parent_id' => null]);
            UserRoleSidebar::where('sidebar_id', $cataloging->id)->delete();
            $cataloging->delete();
        }
    }
};
