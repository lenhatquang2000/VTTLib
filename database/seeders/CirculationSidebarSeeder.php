<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sidebar;
use App\Models\User;
use App\Models\Role;

class CirculationSidebarSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create the Circulation parent sidebar item
        $circulationParent = Sidebar::where('name', 'Circulation')->first();
        
        if (!$circulationParent) {
            $this->command->error('Circulation parent sidebar item not found. Please run the main sidebar seeder first.');
            return;
        }

        // Create the "Thông tin phân phối" sidebar item
        $distributionItem = Sidebar::updateOrCreate(
            [
                'name' => 'Thông tin phân phối',
                'parent_id' => $circulationParent->id,
            ],
            [
                'route_name' => 'admin.circulation.distribution',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
                'is_active' => true,
                'order' => 6, // Place after other circulation items
            ]
        );

        // Assign to all admin roles
        $adminRoles = Role::where('name', 'like', '%admin%')->orWhere('name', 'librarian')->get();
        
        foreach ($adminRoles as $role) {
            $roleUsers = $role->users;
            
            foreach ($roleUsers as $user) {
                $roleUser = $user->roles()->where('role_id', $role->id)->first()->pivot;
                
                // Check if already assigned
                $existing = \App\Models\UserRoleSidebar::where('role_user_id', $roleUser->id)
                    ->where('sidebar_id', $distributionItem->id)
                    ->first();
                
                if (!$existing) {
                    \App\Models\UserRoleSidebar::create([
                        'role_user_id' => $roleUser->id,
                        'sidebar_id' => $distributionItem->id,
                    ]);
                }
            }
        }

        $this->command->info('✅ Added "Thông tin phân phối" sidebar item successfully!');
    }
}
