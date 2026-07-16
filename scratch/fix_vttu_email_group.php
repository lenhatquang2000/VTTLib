<?php
try {
    $mySql = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8mb4", "tthieu", "Tthieu$2025!");
    $mySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Tìm ID nhóm Cán Bộ
    $stmt = $mySql->query("SELECT id, name, code FROM patron_groups ORDER BY id");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $canBoGroup = null;
    foreach ($groups as $g) {
        if (stripos($g['name'], 'cán bộ') !== false || stripos($g['name'], 'can bo') !== false) {
            $canBoGroup = $g;
            break;
        }
    }

    if (!$canBoGroup) {
        echo "⚠️  Không tìm thấy nhóm Cán Bộ!\n";
        exit;
    }
    echo "✅ Nhóm Cán Bộ: ID={$canBoGroup['id']} [{$canBoGroup['code']}] {$canBoGroup['name']}\n\n";

    // 2. Lấy danh sách patron_detail.id cần cập nhật
    $stmt2 = $mySql->prepare("
        SELECT pd.id, pd.patron_code, pd.display_name, pd.patron_group_id, u.email, pg.name AS group_name
        FROM patron_details pd
        JOIN users u ON u.id = pd.user_id
        JOIN patron_groups pg ON pg.id = pd.patron_group_id
        WHERE u.email LIKE '%@vttu.edu.vn'
          AND pd.patron_group_id != ?
    ");
    $stmt2->execute([$canBoGroup['id']]);
    $records = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    if (empty($records)) {
        echo "✅ Không có bản ghi nào cần cập nhật.\n";
        exit;
    }

    echo "Đang chuyển " . count($records) . " bản ghi sang nhóm Cán Bộ...\n\n";

    // 3. Cập nhật
    $ids = array_column($records, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params = array_merge([$canBoGroup['id']], $ids);

    $updateStmt = $mySql->prepare("UPDATE patron_details SET patron_group_id = ?, updated_at = NOW() WHERE id IN ({$placeholders})");
    $updateStmt->execute($params);
    $affected = $updateStmt->rowCount();

    echo "✅ Đã cập nhật thành công {$affected} bản ghi sang nhóm [{$canBoGroup['code']}] {$canBoGroup['name']}!\n\n";

    // 4. In danh sách đã cập nhật
    echo "Danh sách đã chuyển nhóm:\n";
    echo str_repeat("-", 80) . "\n";
    foreach ($records as $r) {
        echo "  - [{$r['patron_code']}] {$r['display_name']} | {$r['email']} | {$r['group_name']} → {$canBoGroup['name']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
