<?php
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "Log size: " . filesize($logFile) . " bytes\n";
    $lines = file($logFile);
    echo "Last 20 lines:\n";
    foreach (array_slice($lines, -20) as $line) {
        echo $line;
    }
} else {
    echo "No laravel.log found.\n";
}
