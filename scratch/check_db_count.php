<?php
try {
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = [
        'users' => "SELECT COUNT(*) FROM users",
        'patron_details' => "SELECT COUNT(*) FROM patron_details",
        'loan_transactions' => "SELECT COUNT(*) FROM loan_transactions",
        'patron_transactions' => "SELECT COUNT(*) FROM patron_transactions",
        'patron_card_renewals' => "SELECT COUNT(*) FROM patron_card_renewals",
        'patron_lock_history' => "SELECT COUNT(*) FROM patron_lock_history"
    ];
    
    echo "Current MySQL (New Database) Record Counts:\n";
    foreach ($tables as $table => $sql) {
        try {
            $stmt = $mySql->query($sql);
            $count = $stmt->fetchColumn();
            echo "  - {$table}: {$count} records\n";
        } catch (Exception $e) {
            echo "  - {$table}: Table not found or error: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
