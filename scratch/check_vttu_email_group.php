<?php
try {
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8mb4", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Tìm nhóm "Cán Bộ"
    $stmt = $mySql->query("SELECT id, name, code FROM patron_groups ORDER BY id");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Danh sách nhóm độc giả:\n";
    foreach ($groups as $g) {
        echo "  - ID {$g['id']}: [{$g['code']}] {$g['name']}\n";
    }

    // Tìm ID nhóm Cán Bộ
    $canBoGroup = null;
    foreach ($groups as $g) {
        if (stripos($g['name'], 'cán bộ') !== false || stripos($g['name'], 'can bo') !== false || stripos($g['code'], 'CB') !== false) {
            $canBoGroup = $g;
            break;
        }
    }

    if (!$canBoGroup) {
        echo "\n⚠️  Không tìm thấy nhóm Cán Bộ!\n";
        exit;
    }
    echo "\n✅ Nhóm Cán Bộ: ID={$canBoGroup['id']} [{$canBoGroup['code']}] {$canBoGroup['name']}\n\n";

    // 2. Tìm các độc giả có email @vttu.edu.vn nhưng KHÔNG thuộc nhóm Cán Bộ
    $stmt2 = $mySql->prepare("
        SELECT pd.id, pd.patron_code, pd.display_name, pd.patron_group_id, u.email, pg.name AS group_name
        FROM patron_details pd
        JOIN users u ON u.id = pd.user_id
        JOIN patron_groups pg ON pg.id = pd.patron_group_id
        WHERE u.email LIKE '%@vttu.edu.vn'
          AND pd.patron_group_id != ?
        ORDER BY pd.patron_code
    ");
    $stmt2->execute([$canBoGroup['id']]);
    $wrongGroup = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo "Độc giả có email @vttu.edu.vn nhưng KHÔNG thuộc nhóm Cán Bộ:\n";
    echo str_repeat("-", 80) . "\n";
    if (empty($wrongGroup)) {
        echo "  → Không có bản ghi nào cần cập nhật.\n";
    } else {
        foreach ($wrongGroup as $r) {
            echo "  - [{$r['patron_code']}] {$r['display_name']} | Email: {$r['email']} | Nhóm hiện tại: {$r['group_name']}\n";
        }
        echo "\nTổng: " . count($wrongGroup) . " bản ghi cần chuyển sang nhóm Cán Bộ.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
