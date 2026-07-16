<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = DB::table('patron_details')->whereNotNull('deleted_at')->count();
echo "Deleted patrons count: $count\n";
