<?php
try {
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Find all loan-related tables
    $stmt = $sqlSrv->query("SELECT TABLE_NAME, TABLE_TYPE FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE '%CHECKOUT%' OR TABLE_NAME LIKE '%LOAN%' OR TABLE_NAME LIKE '%BORROW%' OR TABLE_NAME LIKE '%HISTORY%' OR TABLE_NAME LIKE '%CIRC%' ORDER BY TABLE_NAME");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Loan/History related tables in SQL Server:\n";
    foreach ($tables as $t) {
        $count = $sqlSrv->query("SELECT COUNT(*) FROM {$t['TABLE_NAME']}")->fetchColumn();
        echo "  - {$t['TABLE_NAME']} ({$t['TABLE_TYPE']}): {$count} records\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
