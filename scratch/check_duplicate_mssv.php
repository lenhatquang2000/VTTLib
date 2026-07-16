<?php
try {
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check in MySQL
    $stmt = $mySql->prepare("SELECT patron_code, mssv, display_name, card_status FROM patron_details WHERE mssv = ?");
    $stmt->execute(['079204017810']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "MySQL records with MSSV '079204017810':\n";
    foreach ($rows as $row) {
        echo "  - Code: {$row['patron_code']} | Name: {$row['display_name']} | Status: {$row['card_status']} | MSSV: {$row['mssv']}\n";
    }
    
    // Check in SQL Server
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt2 = $sqlSrv->prepare("SELECT PATRONID, PATRONNAME, OTHERID FROM PATRONS WHERE OTHERID = ? OR PATRONID = ?");
    $stmt2->execute(['079204017810', '079204017810']);
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nSQL Server PATRONS records with ID/OTHERID '079204017810':\n";
    foreach ($rows2 as $row) {
        echo "  - PATRONID: {$row['PATRONID']} | Name: {$row['PATRONNAME']} | OTHERID: {$row['OTHERID']}\n";
    }
    
    $stmt3 = $sqlSrv->prepare("SELECT PATRONID, FIRSTNAME, LASTNAME, OTHERID FROM PATRONSREGISTER WHERE OTHERID = ? OR PATRONID = ?");
    $stmt3->execute(['079204017810', '079204017810']);
    $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nSQL Server PATRONSREGISTER records with ID/OTHERID '079204017810':\n";
    foreach ($rows3 as $row) {
        echo "  - PATRONID: {$row['PATRONID']} | Name: {$row['FIRSTNAME']} {$row['LASTNAME']} | OTHERID: {$row['OTHERID']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
