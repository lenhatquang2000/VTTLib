<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $sqlSrv = new \PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    // Get all visible patrons from old database
    $stmt = $sqlSrv->query("SELECT PATRONID, PATRONNAME, PATRONTYPE FROM PATRONS WHERE INVISIBLE IS NULL OR INVISIBLE != 1");
    $visible = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total Visible in old DB: " . count($visible) . "\n";
    
    // Let's check if there are test/admin names
    foreach ($visible as $v) {
        $name = trim($v['PATRONNAME']);
        $id = trim($v['PATRONID']);
        if (preg_match('/admin|test|he thong|hệ thống|thử|atech|sa/i', $name) || preg_match('/admin|test|sa/i', $id)) {
            echo "Possible Admin/Test in Old DB: ID: $id - Name: $name - Type: {$v['PATRONTYPE']}\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
