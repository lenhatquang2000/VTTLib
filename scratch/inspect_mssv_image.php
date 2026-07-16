<?php
try {
    // 1. Connect to SQL Server
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Find patron by PATRONID or OTHERID (MSSV)
    $stmt = $sqlSrv->prepare("SELECT PATRONID, PATRONNAME, OTHERID, PATRONPICTURE FROM PATRONS WHERE PATRONID = ? OR OTHERID = ?");
    $stmt->execute(['1558461660', '1558461660']);
    $patron = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "SQL Server (Old DB) result:\n";
    if ($patron) {
        $hasPic = !empty($patron['PATRONPICTURE']) ? "YES" : "NO";
        
        $picSize = 0;
        if (!empty($patron['PATRONPICTURE'])) {
            $imgData = $patron['PATRONPICTURE'];
            if (is_resource($imgData)) {
                $imgData = stream_get_contents($imgData);
            }
            $picSize = strlen($imgData);
        }
        
        echo "  - PATRONID: " . $patron['PATRONID'] . "\n";
        echo "  - PATRONNAME: " . $patron['PATRONNAME'] . "\n";
        echo "  - OTHERID (MSSV): " . $patron['OTHERID'] . "\n";
        echo "  - Has PATRONPICTURE blob: " . $hasPic . " (Size: " . $picSize . " bytes)\n";
    } else {
        echo "Patron not found in SQL Server.\n";
    }
    echo "\n";
    
    // 2. Connect to MySQL
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt2 = $mySql->prepare("SELECT patron_code, mssv, display_name, profile_image FROM patron_details WHERE patron_code = ? OR mssv = ?");
    $stmt2->execute(['1558461660', '1558461660']);
    $myPatron = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    echo "MySQL (New DB) result:\n";
    if ($myPatron) {
        echo "  - patron_code: " . $myPatron['patron_code'] . "\n";
        echo "  - mssv: " . $myPatron['mssv'] . "\n";
        echo "  - display_name: " . $myPatron['display_name'] . "\n";
        echo "  - profile_image: " . ($myPatron['profile_image'] ?? 'NULL') . "\n";
    } else {
        echo "Patron not found in MySQL yet.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
