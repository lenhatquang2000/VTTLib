<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nodes = DB::table('site_nodes')
    ->where('is_active', 1)
    ->select('id', 'node_code', 'display_name', 'url')
    ->get();

foreach ($nodes as $node) {
    echo "ID: {$node->id} | Name: {$node->display_name} | Code: {$node->node_code} | URL: {$node->url}\n";
}
