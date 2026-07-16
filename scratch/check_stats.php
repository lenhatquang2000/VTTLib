<?php
try {
    $conn = new PDO("mysql:host=192.168.1.10;port=3306;dbname=vttu_lib;charset=utf8", "tthieu", "Tthieu$2025!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Count patrons with phone or phone_contact not null/empty
    $total = $conn->query("SELECT COUNT(*) FROM patron_details")->fetchColumn();
    $withPhone = $conn->query("SELECT COUNT(*) FROM patron_details WHERE (phone IS NOT NULL AND phone != '') OR (phone_contact IS NOT NULL AND phone_contact != '')")->fetchColumn();
    
    echo "Total patrons in MySQL: $total\n";
    echo "Patrons with phone numbers: $withPhone\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
