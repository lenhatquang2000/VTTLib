<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\UserRoleSidebar;
use App\Models\Sidebar;

$userId = 1;
$user = User::find($userId);

if (!$user) {
    echo "User ID $userId not found.\n";
    exit;
}

$pivotIds = $user->roles->pluck('pivot.id')->toArray();
echo "User: {$user->name} (ID: {$user->id})\n";
echo "Pivot IDs (role_user): " . implode(', ', $pivotIds) . "\n\n";

$assignedSidebars = UserRoleSidebar::with('sidebar')->whereIn('role_user_id', $pivotIds)->get();

echo "Currently Assigned Individual Tabs:\n";
foreach ($assignedSidebars as $as) {
    $sidebar = $as->sidebar;
    if ($sidebar) {
        echo " - ID: {$sidebar->id} | Name: {$sidebar->name} | Route: {$sidebar->route_name}\n";
    }
}

$targetTabs = [24, 25, 26, 27];
$foundIds = $assignedSidebars->pluck('sidebar_id')->toArray();
$missing = array_diff($targetTabs, $foundIds);

echo "\nVerification of Target Tabs [24, 25, 26, 27]:\n";
if (empty($missing)) {
    echo "✅ SUCCESS: All target tabs are assigned to this user.\n";
} else {
    echo "❌ MISSING Tabs: " . implode(', ', $missing) . "\n";
    echo "These tabs are not assigned to any of the user's current roles in the user_role_sidebars table.\n";
}
