<?php
try {
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Search in PATRONSREGISTER by PATRONID or OTHERID
    $stmt = $sqlSrv->prepare("SELECT PATRONID, FIRSTNAME, LASTNAME, OTHERID, PATRONPICTURE FROM PATRONSREGISTER WHERE PATRONID = ? OR OTHERID = ?");
    $stmt->execute(['20266098', '20266098']);
    $reg = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "SQL Server PATRONSREGISTER search result:\n";
    if ($reg) {
        $hasPic = !empty($reg['PATRONPICTURE']) ? "YES" : "NO";
        echo "  - PATRONID: {$reg['PATRONID']} | Name: {$reg['FIRSTNAME']} {$reg['LASTNAME']} | OTHERID: {$reg['OTHERID']} | Has Photo: $hasPic\n";
    } else {
        echo "Not found in PATRONSREGISTER.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
