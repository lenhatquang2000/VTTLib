<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteNodeEnglishDisplayNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $englishNames = [
            'home' => 'Home',
            'gioi-thieu' => 'Introduction',
            'tra-cuu-opac' => 'OPAC Search',
            'lien-he' => 'Contact',
            'about' => 'About Us',
            'services' => 'Services',
            'contact' => 'Contact & Support',
            'digital-library' => 'Digital Library',
            'thoi-gian-phuc-vu' => 'Opening Hours',
            'ban-do-website-thu-vien' => 'Sitemap',
            'huong-dan' => 'Guide',
            'tai-app-mobile' => 'Download Mobile App',
            'dang-nhap-tai-khoan' => 'Account Login',
            'doi-mat-khau' => 'Change Password',
            'tra-cuu-tai-lieu-giay' => 'Search Print Resources',
            'tra-cuu-tai-lieu-so' => 'Search Digital Resources',
            'muon-truoc-gia-han' => 'Pre-borrow & Renew',
            'de-nghi-bo-sung' => 'Request Acquisition',
            'tai-nguyen' => 'Resources',
            'tai-lieu-giay' => 'Print Resources',
            'tai-lieu-so' => 'Digital Resources',
            'co-so-du-lieu' => 'Databases',
            'tai-nguyen-giao-duc-mo' => 'Open Educational Resources',
            'tin-tuc' => 'News',
            'sb-tai-lieu-dien-tu' => 'Electronic Resources',
            'sb-co-so-du-lieu' => 'Databases',
            'sb-hoc-lieu-vttu' => 'VTTU Learning Materials',
            'sb-video-bai-giang' => 'Video Lectures',
            'sb-de-nghi-bo-sung' => 'Request Acquisition',
            'sb-khao-sat' => 'Patron Survey',
            'footer-lien-he' => 'Contact Information',
            'gioi-thieu-chung' => 'General Introduction',
            'chuc-nang-nhiem-vu' => 'Functions & Tasks',
            'noi-quy-thu-vien' => 'Library Regulations',
            'cam-nang-hdsd' => 'Library User Handbook',
        ];

        foreach ($englishNames as $code => $nameEn) {
            \Illuminate\Support\Facades\DB::table('site_nodes')
                ->where('node_code', $code)
                ->update(['display_name_en' => $nameEn]);
        }
    }
}
