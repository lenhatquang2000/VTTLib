<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Get all sidebar items
$sidebars = App\Models\Sidebar::select('id', 'name', 'icon')->get();

echo "Current Sidebar Items:\n";
echo "=====================\n";

foreach ($sidebars as $sidebar) {
    echo "ID: {$sidebar->id}\n";
    echo "Name: {$sidebar->name}\n";
    echo "Icon: " . substr($sidebar->icon, 0, 100) . "...\n";
    echo "---------------------\n";
}
