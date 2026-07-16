<?php
try {
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get column names
    $stmt = $sqlSrv->query("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'CIRCULATIONTRANS' ORDER BY ORDINAL_POSITION");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "CIRCULATIONTRANS columns:\n";
    foreach ($cols as $col) {
        echo "  - {$col['COLUMN_NAME']} ({$col['DATA_TYPE']})\n";
    }
    
    // Get sample data
    echo "\nSample (TOP 3 rows):\n";
    $stmt2 = $sqlSrv->query("SELECT TOP 3 * FROM CIRCULATIONTRANS ORDER BY TRANSDATE DESC");
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $i => $row) {
        echo "Row " . ($i+1) . ":\n";
        foreach ($row as $k => $v) {
            echo "  {$k}: {$v}\n";
        }
        echo "\n";
    }
    
    // Check how many are returns vs checkouts
    $stmt3 = $sqlSrv->query("SELECT TRANSTYPE, COUNT(*) as cnt FROM CIRCULATIONTRANS GROUP BY TRANSTYPE");
    $types = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    echo "Breakdown by TRANSTYPE:\n";
    foreach ($types as $t) {
        echo "  - {$t['TRANSTYPE']}: {$t['cnt']} records\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
