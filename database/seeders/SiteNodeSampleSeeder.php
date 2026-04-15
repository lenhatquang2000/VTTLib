<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteNodeSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample site nodes
        $nodes = [
            [
                'node_code' => 'home',
                'node_name' => 'Trang chủ',
                'display_name' => 'Trang chủ',
                'description' => 'Trang chủ của thư viện số',
                'parent_id' => null,
                'icon' => 'fas fa-home',
                'display_type' => 'menu',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => '<h2>Chào mừng đến với Thư viện số!</h2>
<p>Thư viện số là nền tảng quản lý thư viện hiện đại, giúp bạn dễ dàng tra cứu, mượn và quản lý tài liệu thư viện một cách hiệu quả.</p>
<h3>Tính năng nổi bật:</h3>
<ul>
<li>Tra cứu OPAC trực tuyến</li>
<li>Quản lý mượn trả tự động</li>
<li>Quản lý thông tin bạn đọc</li>
<li>Báo cáo thống kê chi tiết</li>
</ul>',
                'route_name' => null,
                'url' => null,
                'sort_order' => 1,
                'language' => 'vi',
                'meta_title' => 'Trang chủ - Thư viện số',
                'meta_description' => 'Trang chủ của thư viện số - Nền tảng quản lý thư viện hiện đại',
                'meta_keywords' => 'thư viện số, trang chủ, quản lý thư viện',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'node_code' => 'gioi-thieu',
                'node_name' => 'Giới thiệu',
                'display_name' => 'Giới thiệu',
                'description' => 'Giới thiệu về thư viện số',
                'parent_id' => null,
                'icon' => 'fas fa-info-circle',
                'display_type' => 'menu',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => '<h2>Giới thiệu về Thư viện số</h2>
<p>Thư viện số được phát triển với mục tiêu mang đến giải pháp quản lý thư viện toàn diện, hiện đại và hiệu quả.</p>
<h3>Lịch sử hình thành</h3>
<p>Dự án bắt đầu từ năm 2024 với sự tham gia của các chuyên gia thư viện và công nghệ thông tin.</p>
<h3>Sứ mệnh</h3>
<p>Phục vụ cộng đồng đọc giả với nền tảng công nghệ tiên tiến, giúp việc tiếp cận tri thức trở nên dễ dàng hơn.</p>',
                'route_name' => null,
                'url' => null,
                'sort_order' => 2,
                'language' => 'vi',
                'meta_title' => 'Giới thiệu - Thư viện số',
                'meta_description' => 'Giới thiệu về thư viện số, lịch sử hình thành và sứ mệnh',
                'meta_keywords' => 'giới thiệu, thư viện số, lịch sử, sứ mệnh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'node_code' => 'dich-vu',
                'node_name' => 'Dịch vụ',
                'display_name' => 'Dịch vụ',
                'description' => 'Các dịch vụ của thư viện',
                'parent_id' => null,
                'icon' => 'fas fa-concierge-bell',
                'display_type' => 'menu',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => '<h2>Các dịch vụ của thư viện</h2>
<p>Thư viện số cung cấp nhiều dịch vụ hữu ích cho bạn đọc:</p>
<ul>
<li>Tra cứu tài liệu trực tuyến</li>
<li>Đăng ký thẻ thư viện</li>
<li>Mượn và trả sách</li>
<li>Tư vấn nghiên cứu</li>
</ul>',
                'route_name' => null,
                'url' => null,
                'sort_order' => 3,
                'language' => 'vi',
                'meta_title' => 'Dịch vụ - Thư viện số',
                'meta_description' => 'Các dịch vụ của thư viện số',
                'meta_keywords' => 'dịch vụ, thư viện số, tra cứu, mượn sách',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'node_code' => 'tra-cuu-opac',
                'node_name' => 'Tra cứu OPAC',
                'display_name' => 'Tra cứu OPAC',
                'description' => 'Hệ thống tra cứu trực tuyến',
                'parent_id' => null,
                'icon' => 'fas fa-search',
                'display_type' => 'menu',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => null,
                'route_name' => 'opac.search',
                'url' => null,
                'sort_order' => 4,
                'language' => 'vi',
                'meta_title' => 'Tra cứu OPAC - Thư viện số',
                'meta_description' => 'Hệ thống tra cứu trực tuyến công cộng OPAC',
                'meta_keywords' => 'tra cứu, OPAC, tìm kiếm tài liệu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'node_code' => 'tin-tuc',
                'node_name' => 'Tin tức',
                'display_name' => 'Tin tức',
                'description' => 'Tin tức và sự kiện',
                'parent_id' => null,
                'icon' => 'fas fa-newspaper',
                'display_type' => 'menu',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => '<h2>Tin tức và Sự kiện</h2>
<p>Cập nhật các tin tức và sự kiện mới nhất của thư viện.</p>
<p><em>Chức năng tin tức đang được phát triển...</em></p>',
                'route_name' => null,
                'url' => null,
                'sort_order' => 5,
                'language' => 'vi',
                'meta_title' => 'Tin tức - Thư viện số',
                'meta_description' => 'Tin tức và sự kiện của thư viện',
                'meta_keywords' => 'tin tức, sự kiện, thư viện',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'node_code' => 'lien-he',
                'node_name' => 'Liên hệ',
                'display_name' => 'Liên hệ',
                'description' => 'Thông tin liên hệ',
                'parent_id' => null,
                'icon' => 'fas fa-phone',
                'display_type' => 'footer',
                'target' => '_self',
                'is_active' => true,
                'access_type' => 'public',
                'allowed_roles' => null,
                'allow_guest' => true,
                'content' => '<h2>Liên hệ với chúng tôi</h2>
<p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM</p>
<p><strong>Điện thoại:</strong> (028) 1234 5678</p>
<p><strong>Email:</strong> info@thuvienso.vn</p>
<p><strong>Giờ làm việc:</strong></p>
<ul>
<li>Thứ 2 - Thứ 6: 8:00 - 17:00</li>
<li>Thứ 7: 8:00 - 12:00</li>
<li>Chủ nhật: Nghỉ</li>
</ul>',
                'route_name' => null,
                'url' => null,
                'sort_order' => 6,
                'language' => 'vi',
                'meta_title' => 'Liên hệ - Thư viện số',
                'meta_description' => 'Thông tin liên hệ thư viện số',
                'meta_keywords' => 'liên hệ, địa chỉ, điện thoại, email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('site_nodes')->insert($nodes);

        $this->command->info('Sample site nodes created successfully!');
    }
}
