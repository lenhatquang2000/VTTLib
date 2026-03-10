<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Mapping of sidebar names to FontAwesome icons
$iconMapping = [
    'Dashboard' => '<i class="fas fa-tachometer-alt"></i>',
    'MARC Framework' => '<i class="fas fa-book-open"></i>',
    'Books' => '<i class="fas fa-book"></i>',
    'Loans' => '<i class="fas fa-hand-holding-usd"></i>',
    'Cataloging' => '<i class="fas fa-layer-group"></i>',
    'Patron Management' => '<i class="fas fa-users"></i>',
    'Patron List' => '<i class="fas fa-list"></i>',
    'Add New Patron' => '<i class="fas fa-user-plus"></i>',
    'System Management' => '<i class="fas fa-cogs"></i>',
    'System Settings' => '<i class="fas fa-cog"></i>',
    'Circulation' => '<i class="fas fa-sync-alt"></i>',
    'Policies' => '<i class="fas fa-clipboard-list"></i>',
    'Loan Desk' => '<i class="fas fa-desktop"></i>',
    'Fines' => '<i class="fas fa-dollar-sign"></i>',
    'Document Types' => '<i class="fas fa-file-alt"></i>',
    'Z39.50 Servers' => '<i class="fas fa-server"></i>',
    'User Privilege Management' => '<i class="fas fa-shield-alt"></i>',
    'Role Management' => '<i class="fas fa-user-tag"></i>',
    'Patron Categories' => '<i class="fas fa-users-cog"></i>',
    'System Logs' => '<i class="fas fa-file-alt"></i>',
    'Users List' => '<i class="fas fa-list-ul"></i>',
    'User Management' => '<i class="fas fa-user-cog"></i>',
];

echo "Updating Sidebar Icons...\n";
echo "========================\n";

$sidebars = App\Models\Sidebar::all();

foreach ($sidebars as $sidebar) {
    if (isset($iconMapping[$sidebar->name])) {
        $oldIcon = $sidebar->icon;
        $newIcon = $iconMapping[$sidebar->name];
        
        // Update the icon
        $sidebar->icon = $newIcon;
        $sidebar->save();
        
        echo "Updated: {$sidebar->name}\n";
        echo "  Old: " . substr($oldIcon, 0, 50) . "...\n";
        echo "  New: {$newIcon}\n";
        echo "---------------------\n";
    } else {
        echo "No mapping found for: {$sidebar->name}\n";
        echo "  Current: " . substr($sidebar->icon, 0, 50) . "...\n";
        echo "---------------------\n";
    }
}

echo "\nUpdate completed!\n";
