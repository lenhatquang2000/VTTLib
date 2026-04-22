<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

app()->setLocale('vi');
echo "Locale: " . app()->getLocale() . PHP_EOL;
echo PHP_EOL;

$keys = [
    'sidebar.dashboard',
    'sidebar.cataloging',
    'sidebar.content_management',
    'sidebar.user_management',
    'sidebar.patron_management',
    'sidebar.system_management',
    'sidebar.circulation',
    'sidebar.loans',
];

foreach ($keys as $key) {
    $translated = __($key);
    $match = ($translated !== $key) ? 'OK' : 'FAIL';
    echo "[$match] $key => $translated" . PHP_EOL;
}
