<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RoleSidebar;
use App\Models\Role;
use App\Models\Sidebar;

$roles = Role::whereIn('id', [1, 3])->get();
$targetIds = [24, 25, 26, 27];

echo "Sidebar ID mapping check:\n";
foreach ($targetIds as $id) {
    $s = Sidebar::find($id);
    echo "- ID: $id | Name: " . ($s ? $s->name : "MISSING!") . "\n";
}

echo "\nRole Sidebar Templates (role_sidebars table):\n";
foreach ($roles as $r) {
    $templateIds = RoleSidebar::where('role_id', $r->id)->pluck('sidebar_id')->toArray();
    $found = array_intersect($targetIds, $templateIds);
    $missing = array_diff($targetIds, $templateIds);
    
    echo "\nRole: [{$r->id}] {$r->name} (Display: {$r->display_name})\n";
    echo "Found target IDs: " . (empty($found) ? "NONE" : implode(', ', $found)) . "\n";
    echo "Missing target IDs: " . (empty($missing) ? "NONE" : implode(', ', $missing)) . "\n";
    echo "Total Template Sidebar count: " . count($templateIds) . "\n";
}
