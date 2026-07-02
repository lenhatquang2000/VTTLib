<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    /**
     * Seed news categories phù hợp với homepage VTTU Library.
     *
     * Cấu trúc:
     *  - Tin tức sự kiện  → $homeNews (khối lớn trái homepage)
     *  - Tin mới          → tab TIN MỚI (news_type=news)
     *  - Video            → tab VIDEO   (news_type=video)
     *  - Thông báo        → sidebar Announcement
     *  - Sản khoa         → tab Y khoa (medical-tabs)
     *  - Nhi khoa         → tab Y khoa (medical-tabs)
     *  - Nội khoa         → tab Y khoa (medical-tabs)
     */
    public function run(): void
    {
        $categories = [
            // -------------------------------------------------------
            // 1. TIN TỨC SỰ KIỆN — Block lớn bên trái homepage
            // -------------------------------------------------------
            [
                'name'             => 'Tin tức sự kiện',
                'name_en'          => 'News & Events',
                'slug'             => 'tin-tuc-su-kien',
                'description'      => 'Tin tức, sự kiện nổi bật của thư viện VTTU.',
                'color'            => '#b91c1c',
                'icon'             => 'newspaper',
                'parent_id'        => null,
                'sort_order'       => 1,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 2. TIN MỚI — Tab "news" trong Section 3 homepage
            // -------------------------------------------------------
            [
                'name'             => 'Tin mới',
                'name_en'          => 'Latest News',
                'slug'             => 'tin-moi',
                'description'      => 'Các bài viết tin tức mới nhất.',
                'color'            => '#0ea5e9',
                'icon'             => 'rss',
                'parent_id'        => null,
                'sort_order'       => 2,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 3. VIDEO — Tab "video" trong Section 3 homepage
            // -------------------------------------------------------
            [
                'name'             => 'Video',
                'name_en'          => 'Video',
                'slug'             => 'video',
                'description'      => 'Các video giới thiệu, hướng dẫn và sự kiện thư viện.',
                'color'            => '#7c3aed',
                'icon'             => 'video',
                'parent_id'        => null,
                'sort_order'       => 3,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 4. THÔNG BÁO — Sidebar Announcements
            // -------------------------------------------------------
            [
                'name'             => 'Thông báo',
                'name_en'          => 'Announcements',
                'slug'             => 'thong-bao',
                'description'      => 'Thông báo chính thức từ thư viện VTTU.',
                'color'            => '#f59e0b',
                'icon'             => 'bell',
                'parent_id'        => null,
                'sort_order'       => 4,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 5. SẢN KHOA — Tab y khoa Section 5 homepage
            // -------------------------------------------------------
            [
                'name'             => 'Sản khoa',
                'name_en'          => 'Obstetrics',
                'slug'             => 'san-khoa',
                'description'      => 'Tài liệu và tin tức chuyên ngành Sản khoa.',
                'color'            => '#ec4899',
                'icon'             => 'heart',
                'parent_id'        => null,
                'sort_order'       => 5,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 6. NHI KHOA — Tab y khoa Section 5 homepage
            // -------------------------------------------------------
            [
                'name'             => 'Nhi khoa',
                'name_en'          => 'Pediatrics',
                'slug'             => 'nhi-khoa',
                'description'      => 'Tài liệu và tin tức chuyên ngành Nhi khoa.',
                'color'            => '#10b981',
                'icon'             => 'baby',
                'parent_id'        => null,
                'sort_order'       => 6,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 7. NỘI KHOA — Tab y khoa Section 5 homepage
            // -------------------------------------------------------
            [
                'name'             => 'Nội khoa',
                'name_en'          => 'Internal Medicine',
                'slug'             => 'noi-khoa',
                'description'      => 'Tài liệu và tin tức chuyên ngành Nội khoa.',
                'color'            => '#3b82f6',
                'icon'             => 'stethoscope',
                'parent_id'        => null,
                'sort_order'       => 7,
                'is_active'        => true,
                'language'         => 'vi',
            ],

            // -------------------------------------------------------
            // 8. GIỚI THIỆU SÁCH — Section 4 homepage (Book of Month)
            // -------------------------------------------------------
            [
                'name'             => 'Giới thiệu sách',
                'name_en'          => 'Book Introduction',
                'slug'             => 'gioi-thieu-sach',
                'description'      => 'Các bài giới thiệu sách nổi bật hàng tháng.',
                'color'            => '#f97316',
                'icon'             => 'book-open',
                'parent_id'        => null,
                'sort_order'       => 8,
                'is_active'        => true,
                'language'         => 'vi',
            ],
        ];

        foreach ($categories as $data) {
            DB::table('news_categories')->updateOrInsert(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ NewsCategory: đã seed ' . count($categories) . ' danh mục tin tức.');
    }
}
