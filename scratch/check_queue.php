<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $dbTime = DB::select("SELECT NOW() as t")[0]->t;
    $phpTime = date('Y-m-d H:i:s');
    
    echo "DB Time: $dbTime\n";
    echo "PHP Time: $phpTime\n";
    
    $jobs = DB::table('jobs')->get();
    foreach ($jobs as $j) {
        echo "Job ID: {$j->id} - Available At: " . date('Y-m-d H:i:s', $j->available_at) . " - Queue: {$j->queue} - Attempts: {$j->attempts}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
