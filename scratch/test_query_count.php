<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\PatronReportController;
use Illuminate\Http\Request;

$controller = app(PatronReportController::class);
$request = Request::create('/topsecret/patron-reports', 'GET', ['report_type' => 'patron_list']);

$query = $controller->buildQuery($request);
$total = $query->count();
echo "Simulated Query Total: " . $total . "\n";
