<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sidebar;
use App\Models\User;
use App\Models\UserRoleSidebar;

// 1. Create a top-level "User Management" parent if not exists
$usersParent = Sidebar::where('name', 'User Management')->whereNull('parent_id')->first();
if (!$usersParent) {
    $usersParent = Sidebar::create([
        'name' => 'User Management',
        'route_name' => '#',
        'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
        'order' => 2 // Place right after Dashboard (order 1)
    ]);
    echo "Created parent sidebar: User Management (ID: {$usersParent->id})\n";
}

// 2. Move children to this parent
$userList = Sidebar::where('route_name', 'admin.users.index')->first();
if ($userList) {
    $userList->update(['parent_id' => $usersParent->id, 'order' => 1, 'name' => 'Users List']);
    echo "Moved 'Users List' to User Management parent.\n";
} else {
    // If it didn't exist, create it now
    $userList = Sidebar::create([
       'name' => 'Users List',
        'route_name' => 'admin.users.index',
        'parent_id' => $usersParent->id,
        'order' => 1
    ]);
    echo "Created 'Users List' under User Management parent.\n";
}

$privileges = Sidebar::where('route_name', 'admin.users.privileges')->first();
if ($privileges) {
    $privileges->update(['parent_id' => $usersParent->id, 'order' => 2]);
    echo "Moved 'User Privilege Management' to User Management parent.\n";
}

$roles = Sidebar::where('route_name', 'admin.roles.index')->first();
if ($roles) {
    $roles->update(['parent_id' => $usersParent->id, 'order' => 3]);
    echo "Moved 'Role Management' to User Management parent.\n";
}

// 3. Assign the new parent and children to admins
// Get all role_user_ids that were assigned to previous parent (ID 13) or children
$roleUserIds = UserRoleSidebar::whereIn('sidebar_id', [13, 24, 25])->pluck('role_user_id')->unique();

$targetSidebars = [$usersParent->id, $userList->id];
if ($privileges) $targetSidebars[] = $privileges->id;
if ($roles) $targetSidebars[] = $roles->id;

foreach ($roleUserIds as $ruid) {
    foreach ($targetSidebars as $sid) {
        $exists = UserRoleSidebar::where('role_user_id', $ruid)->where('sidebar_id', $sid)->exists();
        if (!$exists) {
            UserRoleSidebar::create(['role_user_id' => $ruid, 'sidebar_id' => $sid]);
        }
    }
}

echo "Assignments updated.\n";
echo "Done.\n";
