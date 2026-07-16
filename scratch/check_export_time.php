<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$histories = DB::table('export_histories')->orderBy('created_at', 'desc')->limit(10)->get();
foreach ($histories as $h) {
    echo "ID: {$h->id} - Title: {$h->title} - Format: {$h->format} - Status: {$h->status} - Time: {$h->execution_time_ms} ms - Error: {$h->error_message}\n";
}
