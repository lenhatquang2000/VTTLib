<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sidebar;
use App\Models\Role;
use App\Models\User;

$sidebars = Sidebar::all();
echo "--- ALL SIDEBARS ---\n";
foreach ($sidebars as $s) {
    echo "ID: {$s->id} | Name: {$s->name} | Route: {$s->route_name} | Parent: {$s->parent_id}\n";
}

$user = User::where('email', 'agent@vttlib.com')->first();
if ($user) {
    echo "\n--- ASSIGNED TABS FOR agent@vttlib.com ---\n";
    $tabs = $user->getSidebarTabs();
    foreach ($tabs as $tab) {
        echo "Parent: {$tab->name} ({$tab->route_name})\n";
        foreach ($tab->children as $child) {
            echo "  - Child: {$child->name} ({$child->route_name})\n";
        }
    }
}
