<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $sqlSrv = new \PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    // Fetch all patron codes from old database where INVISIBLE = 1
    $stmt = $sqlSrv->query("SELECT PATRONID FROM PATRONS WHERE INVISIBLE = 1");
    $invisibleCodes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $invisibleCodes = array_map('trim', $invisibleCodes);
    
    echo "Found " . count($invisibleCodes) . " invisible patron codes in SQL Server.\n";
    
    // Update deleted_at for these codes in MySQL patron_details
    $now = now();
    $affectedDetails = 0;
    
    foreach ($invisibleCodes as $code) {
        // Find detail
        $detail = DB::table('patron_details')->where('patron_code', $code)->first();
        if ($detail) {
            DB::table('patron_details')->where('id', $detail->id)->update(['deleted_at' => $now]);
            $affectedDetails++;
        }
    }
    
    echo "Soft-deleted $affectedDetails patron_details records in MySQL.\n";
    
    // Check remaining count in MySQL where deleted_at is null and card_status is normal or locked
    $normalLockedCount = DB::table('patron_details')
        ->whereNull('deleted_at')
        ->whereIn('card_status', ['normal', 'locked'])
        ->count();
    echo "Remaining MySQL normal/locked count: $normalLockedCount\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
