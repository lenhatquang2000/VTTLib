<?php
$content = file_get_contents('lang/vi.json');
$data = json_decode($content, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON ERROR: " . json_last_error_msg() . PHP_EOL;
} else {
    echo "JSON OK - Total keys: " . count($data) . PHP_EOL;
}

// Check specific keys
$check = [
    'sidebar.dashboard',
    'sidebar.cataloging', 
    'sidebar.content_management',
    'sidebar.user_management',
    'sidebar.patron_management',
    'sidebar.circulation',
    'sidebar.loans',
];

foreach ($check as $key) {
    if (isset($data[$key])) {
        echo "[FOUND] $key => " . $data[$key] . PHP_EOL;
    } else {
        echo "[MISSING] $key" . PHP_EOL;
    }
}
