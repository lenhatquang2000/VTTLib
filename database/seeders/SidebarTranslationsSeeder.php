<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidebarTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Parent items
            'Dashboard' => ['vi' => 'Bảng điều khiển', 'en' => 'Dashboard'],
            'Loans' => ['vi' => 'Mượn trả', 'en' => 'Loans'],
            'Cataloging' => ['vi' => 'Biên mục', 'en' => 'Cataloging'],
            'Content Management' => ['vi' => 'Quản lý nội dung', 'en' => 'Content Management'],
            'User Management' => ['vi' => 'Quản lý người dùng', 'en' => 'User Management'],
            'Patron Management' => ['vi' => 'Quản lý bạn đọc', 'en' => 'Patron Management'],
            'System Management' => ['vi' => 'Quản lý hệ thống', 'en' => 'System Management'],
            'Circulation' => ['vi' => 'Lưu thông', 'en' => 'Circulation'],
            'Site Management' => ['vi' => 'Quản lý trang web', 'en' => 'Site Management'],
            'News Management' => ['vi' => 'Quản lý tin tức', 'en' => 'News Management'],
            'System' => ['vi' => 'Hệ thống', 'en' => 'System'],

            // Cataloging children
            'MARC Framework' => ['vi' => 'Khung MARC', 'en' => 'MARC Framework'],
            'Books' => ['vi' => 'Sách', 'en' => 'Books'],
            'Document Types' => ['vi' => 'Loại tài liệu', 'en' => 'Document Types'],
            'Z39.50 Servers' => ['vi' => 'Máy chủ Z39.50', 'en' => 'Z39.50 Servers'],
            'MARC Import' => ['vi' => 'Nhập MARC', 'en' => 'MARC Import'],
            'MARC Export' => ['vi' => 'Xuất MARC', 'en' => 'MARC Export'],
            'MARC Reports' => ['vi' => 'Báo cáo MARC', 'en' => 'MARC Reports'],

            // Patron Management children
            'Patron List' => ['vi' => 'Danh sách bạn đọc', 'en' => 'Patron List'],
            'Add New Patron' => ['vi' => 'Thêm bạn đọc mới', 'en' => 'Add New Patron'],
            'Danh sách chờ in thẻ' => ['vi' => 'Danh sách chờ in thẻ', 'en' => 'Print Queue'],
            'Lược sử khóa độc giả' => ['vi' => 'Lược sử khóa độc giả', 'en' => 'Lock History'],
            'Nhật Ký Hệ Thống Độc Giả' => ['vi' => 'Nhật ký hệ thống độc giả', 'en' => 'Patron System Logs'],
            'Patron Categories' => ['vi' => 'Nhóm bạn đọc', 'en' => 'Patron Categories'],

            // System Management children
            'System Settings' => ['vi' => 'Thiết lập hệ thống', 'en' => 'System Settings'],
            'Sidebar Management' => ['vi' => 'Quản lý Sidebar', 'en' => 'Sidebar Management'],
            'System Logs' => ['vi' => 'Nhật ký hệ thống', 'en' => 'System Logs'],
            'System Logs' => ['vi' => 'Nhật ký hệ thống', 'en' => 'System Logs'],

            // Circulation children
            'Quản lý mượn trả' => ['vi' => 'Quản lý mượn trả', 'en' => 'Loan Management'],
            'Loan Desk' => ['vi' => 'Bàn mượn trả', 'en' => 'Loan Desk'],
            'Fines' => ['vi' => 'Phí phạt', 'en' => 'Fines'],
            'Reports' => ['vi' => 'Báo cáo', 'en' => 'Reports'],
            'Thông tin phân phối' => ['vi' => 'Thông tin phân phối', 'en' => 'Distribution Info'],
            'Chính sách lưu thông' => ['vi' => 'Chính sách lưu thông', 'en' => 'Circulation Policies'],

            // User Management children
            'Users List' => ['vi' => 'Danh sách người dùng', 'en' => 'Users List'],
            'User Privilege Management' => ['vi' => 'Quản lý quyền hạn', 'en' => 'User Privileges'],
            'Role Management' => ['vi' => 'Quản lý vai trò', 'en' => 'Role Management'],

            // Site Management children
            'Site Structure' => ['vi' => 'Cấu trúc trang', 'en' => 'Site Structure'],
            'Tree Management' => ['vi' => 'Quản lý cây', 'en' => 'Tree Management'],
            'Add Node' => ['vi' => 'Thêm Node', 'en' => 'Add Node'],

            // News Management children
            'All News' => ['vi' => 'Tất cả tin tức', 'en' => 'All News'],
            'Create News' => ['vi' => 'Tạo tin tức', 'en' => 'Create News'],
            'Categories' => ['vi' => 'Danh mục', 'en' => 'Categories'],
            'Tags' => ['vi' => 'Thẻ tag', 'en' => 'Tags'],
        ];

        $updated = 0;
        foreach ($translations as $name => $trans) {
            DB::table('sidebars')
                ->where('name', $name)
                ->update([
                    'name_vi' => $trans['vi'],
                    'name_en' => $trans['en'],
                    'updated_at' => now(),
                ]);
            $updated++;
        }

        $this->command->info("✅ Updated {$updated} sidebar items with translations!");
    }
}
