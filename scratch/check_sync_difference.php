<?php
try {
    // 1. Connect to SQL Server
    $sqlSrv = new PDO("sqlsrv:Server=192.168.1.33;Database=TDHVTTOAN;TrustServerCertificate=true", "sa", "@123456");
    $sqlSrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get old counts
    $oldCounts = [
        'patrons_total' => $sqlSrv->query("SELECT COUNT(*) FROM PATRONS")->fetchColumn(),
        'patrons_unique_id' => $sqlSrv->query("SELECT COUNT(DISTINCT PATRONID) FROM PATRONS")->fetchColumn(),
        'renew_logs' => $sqlSrv->query("SELECT COUNT(*) FROM PATRONRENEWCARDLOG")->fetchColumn(),
        'register_patrons' => $sqlSrv->query("SELECT COUNT(*) FROM PATRONSREGISTER")->fetchColumn(),
        'checkouts' => $sqlSrv->query("SELECT COUNT(*) FROM CHECKOUTS")->fetchColumn(),
        'fiscal_trans' => $sqlSrv->query("SELECT COUNT(*) FROM PATRONFISCALTRANS")->fetchColumn(),
        'blocked_logs' => $sqlSrv->query("SELECT COUNT(*) FROM PATRONBLOCKEDTRACKING")->fetchColumn(),
    ];
    
    // 2. Connect to MySQL
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get new counts
    $newCounts = [
        'users' => $mySql->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'patrons_official' => $mySql->query("SELECT COUNT(*) FROM patron_details WHERE card_status != 'pending'")->fetchColumn(),
        'patrons_pending' => $mySql->query("SELECT COUNT(*) FROM patron_details WHERE card_status = 'pending'")->fetchColumn(),
        'renew_logs' => $mySql->query("SELECT COUNT(*) FROM patron_card_renewals")->fetchColumn(),
        'loan_transactions' => $mySql->query("SELECT COUNT(*) FROM loan_transactions")->fetchColumn(),
        'patron_transactions' => $mySql->query("SELECT COUNT(*) FROM patron_transactions")->fetchColumn(),
        'lock_history' => $mySql->query("SELECT COUNT(*) FROM patron_lock_history")->fetchColumn(),
    ];
    
    echo "SUMMARY COMPARISON:\n\n";
    echo "1. Official Patrons (Độc giả chính thức):\n";
    echo "   - Old DB (SQL Server) Total: " . $oldCounts['patrons_total'] . " (Unique: " . $oldCounts['patrons_unique_id'] . ")\n";
    echo "   - New DB (MySQL) Imported: " . $newCounts['patrons_official'] . "\n";
    echo "   - Remaining: " . ($oldCounts['patrons_unique_id'] - $newCounts['patrons_official']) . " records\n\n";
    
    echo "2. Renew Card Logs (Nhật ký gia hạn):\n";
    echo "   - Old DB Total: " . $oldCounts['renew_logs'] . "\n";
    echo "   - New DB Imported: " . $newCounts['renew_logs'] . "\n";
    echo "   - Remaining: " . ($oldCounts['renew_logs'] - $newCounts['renew_logs']) . " records\n\n";
    
    echo "3. Online Registrations (Độc giả đăng ký trực tuyến):\n";
    echo "   - Old DB Total: " . $oldCounts['register_patrons'] . "\n";
    echo "   - New DB Imported: " . $newCounts['patrons_pending'] . "\n";
    echo "   - Remaining: " . ($oldCounts['register_patrons'] - $newCounts['patrons_pending']) . " records\n\n";
    
    echo "4. Current Book Loans (Mượn trả hiện hành):\n";
    echo "   - Old DB Total: " . $oldCounts['checkouts'] . "\n";
    echo "   - New DB Imported: " . $newCounts['loan_transactions'] . "\n";
    echo "   - Remaining: " . ($oldCounts['checkouts'] - $newCounts['loan_transactions']) . " records\n\n";
    
    echo "5. Financial Transactions (Giao dịch tài chính):\n";
    echo "   - Old DB Total: " . $oldCounts['fiscal_trans'] . "\n";
    echo "   - New DB Imported: " . $newCounts['patron_transactions'] . "\n";
    echo "   - Remaining: " . ($oldCounts['fiscal_trans'] - $newCounts['patron_transactions']) . " records\n\n";
    
    echo "6. Lock History (Lịch sử khóa thẻ):\n";
    echo "   - Old DB Total: " . $oldCounts['blocked_logs'] . "\n";
    echo "   - New DB Imported: " . $newCounts['lock_history'] . "\n";
    echo "   - Remaining: " . ($oldCounts['blocked_logs'] - $newCounts['lock_history']) . " records\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
