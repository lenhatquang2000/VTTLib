<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sidebar;
use App\Models\User;

$targetTabs = [24, 25, 26, 27];
$tabs = Sidebar::whereIn('id', $targetTabs)->get();

echo "Tab Detailed Status:\n";
foreach ($tabs as $t) {
    echo "ID: {$t->id} | Name: {$t->name} | Parent ID: " . ($t->parent_id ?? 'NULL') . "\n";
}

$systemMgmt = Sidebar::where('name', 'System Management')->first();
if ($systemMgmt) {
    echo "\nSystem Management Dashboard Entry:\n";
    echo "ID: {$systemMgmt->id} | Name: {$systemMgmt->name} | Route: {$systemMgmt->route_name}\n";
    
    // Check if user has this parent sidebar assigned as well
    $userId = 1;
    $user = User::with('roles')->find($userId);
    $pivotIds = $user->roles->pluck('pivot.id')->toArray();
    
    $assignedParent = \App\Models\UserRoleSidebar::whereIn('role_user_id', $pivotIds)
        ->where('sidebar_id', $systemMgmt->id)
        ->exists();
    
    echo "User 1 has System Management assigned directly? " . ($assignedParent ? "YES" : "NO") . "\n";
}
