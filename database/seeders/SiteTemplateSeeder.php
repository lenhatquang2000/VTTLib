<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'template_code' => 'home',
                'template_name' => 'Trang chủ hiện đại (Waves)',
                'sort_order' => 1,
            ],
            [
                'template_code' => 'about',
                'template_name' => 'Trang giới thiệu (About)',
                'sort_order' => 2,
            ],
            [
                'template_code' => 'help',
                'template_name' => 'Trang hướng dẫn (Help)',
                'sort_order' => 3,
            ],
            [
                'template_code' => 'contact',
                'template_name' => 'Trang liên hệ (Contact)',
                'sort_order' => 4,
            ],
            [
                'template_code' => 'services',
                'template_name' => 'Trang dịch vụ (Services)',
                'sort_order' => 5,
            ],
            [
                'template_code' => 'full-width',
                'template_name' => 'Trang toàn màn hình (Full width)',
                'sort_order' => 6,
            ],
            [
                'template_code' => 'gioi-thieu-chung',
                'template_name' => 'Giới thiệu chung (Blue)',
                'sort_order' => 10,
            ],
            [
                'template_code' => 'chuc-nang-nhiem-vu',
                'template_name' => 'Chức năng nhiệm vụ (Indigo)',
                'sort_order' => 11,
            ],
            [
                'template_code' => 'noi-quy-thu-vien',
                'template_name' => 'Nội quy Thư viện (Amber)',
                'sort_order' => 12,
            ],
            [
                'template_code' => 'thoi-gian-phuc-vu',
                'template_name' => 'Thời gian phục vụ (Emerald)',
                'sort_order' => 13,
            ],
            [
                'template_code' => 'ban-do-website',
                'template_name' => 'Bản đồ Website (Violet)',
                'sort_order' => 14,
            ],
        ];

        foreach ($templates as $template) {
            \App\Models\SiteTemplate::updateOrCreate(
                ['template_code' => $template['template_code']],
                $template
            );
        }
    }
}
