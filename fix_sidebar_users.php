<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sidebar;
use App\Models\User;
use App\Models\RoleUser;

// 1. Create the Sidebar entry for User List if not exists
$userList = Sidebar::where('route_name', 'admin.users.index')->first();
if (!$userList) {
    $userList = Sidebar::create([
        'name' => 'User List',
        'route_name' => 'admin.users.index',
        'icon' => null, // Children usually don't need icons in this theme
        'parent_id' => 13, // System Management
        'order' => 5 // Adjust order as needed
    ]);
    echo "Created Sidebar entry: User List (ID: {$userList->id})\n";
} else {
    echo "Sidebar entry already exists (ID: {$userList->id})\n";
}

// 2. Assign it to all users who have the "System Management" parent (ID 13) or "User Privilege Management" (ID 24)
// Actually, it's better to assign it to all RoleUser pivot entries that have ID 13 or 24 assigned.

$assignments = \App\Models\UserRoleSidebar::whereIn('sidebar_id', [13, 24])->pluck('role_user_id')->unique();

foreach ($assignments as $roleUserId) {
    $exists = \App\Models\UserRoleSidebar::where('role_user_id', $roleUserId)
        ->where('sidebar_id', $userList->id)
        ->exists();
    if (!$exists) {
        \App\Models\UserRoleSidebar::create([
            'role_user_id' => $roleUserId,
            'sidebar_id' => $userList->id
        ]);
        echo "Assigned to RoleUser ID: $roleUserId\n";
    }
}

echo "Done.\n";
