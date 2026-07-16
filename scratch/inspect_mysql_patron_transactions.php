<?php
try {
    $conn = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = ['patron_transactions', 'users', 'patron_details', 'loan_transactions'];
    
    foreach ($tables as $table) {
        echo "=========================================\n";
        echo "Table: $table\n";
        echo "=========================================\n";
        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo "  - {$col['Field']} ({$col['Type']}) " . ($col['Null'] === 'YES' ? "NULL" : "NOT NULL") . ($col['Key'] ? " KEY: {$col['Key']}" : "") . "\n";
        }
        echo "\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
