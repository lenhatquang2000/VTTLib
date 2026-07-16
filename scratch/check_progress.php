<?php
try {
    // 1. Check SQL Server
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt1 = $sqlSrv->prepare("SELECT PATRONID, PATRONNAME, PATRONTYPE FROM PATRONS WHERE PATRONID = ?");
    $stmt1->execute(['1458586005']);
    $oldPatron = $stmt1->fetch(PDO::FETCH_ASSOC);
    
    echo "SQL Server (Old DB):\n";
    if ($oldPatron) {
        print_r($oldPatron);
    } else {
        echo "Patron not found in SQL Server.\n";
    }
    echo "\n";
    
    // 2. Check MySQL
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt2 = $mySql->prepare("
        SELECT pd.patron_code, pd.display_name, pg.code AS group_code, pg.name AS group_name 
        FROM patron_details pd
        LEFT JOIN patron_groups pg ON pd.patron_group_id = pg.id
        WHERE pd.patron_code = ?
    ");
    $stmt2->execute(['1458586005']);
    $newPatron = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    echo "MySQL (New DB):\n";
    if ($newPatron) {
        print_r($newPatron);
    } else {
        echo "Patron not found in MySQL yet (still migrating...).\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
