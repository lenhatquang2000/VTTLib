<?php

namespace Database\Seeders;

use App\Models\OpenEducationalResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OERSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oerResources = [
            [
                'title' => 'CK-12 College Human Biology',
                'resource_type' => 'textbook',
                'language' => 'en',
                'authors' => ['Jean Brainard', 'Rachel Henderson'],
                'subjects' => ['Y học sức khỏe', 'Sinh học'],
                'educational_levels' => ['Đại học'],
                'license' => 'BY-NC',
                'license_url' => 'https://creativecommons.org/licenses/by-nc/4.0/',
                'description' => 'A comprehensive guide to human biology covering molecular, cellular, and systemic levels.',
                'publisher' => 'CK-12 Foundation',
                'publish_year' => '2024',
                'format' => 'PDF',
                'source' => 'CK-12 Foundation',
                'external_link' => 'https://www.ck12.org/',
                'keywords' => 'biology, human biology, college',
                'status' => 'published',
                'created_at' => '2024-02-28 00:00:00',
                'view_count' => 1500,
                'download_count' => 450
            ],
            [
                'title' => 'A Laboratory Guide to Clinical Hematology',
                'resource_type' => 'textbook',
                'language' => 'en',
                'authors' => ['Valentin (Tino) Villatoro', 'Michelle To'],
                'subjects' => ['Y học sức khỏe'],
                'educational_levels' => ['Đại học'],
                'license' => 'BY-NC',
                'license_url' => 'https://creativecommons.org/licenses/by-nc/4.0/',
                'description' => 'A detailed guide for laboratory procedures in hematology.',
                'publisher' => 'University of Alberta Libraries',
                'publish_year' => '2020',
                'format' => 'PDF',
                'source' => 'Open Textbook Library',
                'external_link' => 'https://open.umn.edu/opentextbooks',
                'keywords' => 'hematology, medical, laboratory',
                'status' => 'published',
                'created_at' => '2020-04-21 00:00:00',
                'view_count' => 2800,
                'download_count' => 620
            ],
            [
                'title' => 'An EKG Interpretation Primer',
                'resource_type' => 'guide',
                'language' => 'en',
                'authors' => ['Jacqueline Christianson'],
                'subjects' => ['Y học sức khỏe'],
                'educational_levels' => ['Đại học'],
                'license' => 'CC BY',
                'license_url' => 'https://creativecommons.org/licenses/by/4.0/',
                'description' => 'Basic introduction to EKG interpretation for healthcare professionals.',
                'publisher' => 'Nurses International Team',
                'publish_year' => '2022',
                'format' => 'PDF',
                'source' => 'Nurses International',
                'external_link' => 'https://nursesinternational.org/',
                'keywords' => 'EKG, cardiology, nursing',
                'status' => 'published',
                'view_count' => 3100,
                'download_count' => 890
            ],
            [
                'title' => 'Principles of Microeconomics',
                'resource_type' => 'textbook',
                'language' => 'en',
                'authors' => ['Libby Rittenberg', 'Timothy Tregarthen'],
                'subjects' => ['Kinh tế'],
                'educational_levels' => ['Đại học'],
                'license' => 'CC BY-NC-SA',
                'license_url' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
                'description' => 'Core principles of microeconomics for university students.',
                'publisher' => 'Saylor Academy',
                'publish_year' => '2023',
                'format' => 'PDF',
                'status' => 'published',
                'view_count' => 4500,
                'download_count' => 1200
            ],
            [
                'title' => 'Introduction to Intellectual Property',
                'resource_type' => 'course',
                'language' => 'en',
                'authors' => ['Legal Experts'],
                'subjects' => ['Luật'],
                'educational_levels' => ['Đại học'],
                'license' => 'CC BY',
                'license_url' => 'https://creativecommons.org/licenses/by/4.0/',
                'description' => 'Foundational concepts of IP law including patents, trademarks, and copyright.',
                'publisher' => 'WIPO Academy',
                'publish_year' => '2021',
                'format' => 'Online',
                'status' => 'published',
                'view_count' => 2100,
                'download_count' => 300
            ]
        ];

        foreach ($oerResources as $oer) {
            OpenEducationalResource::updateOrCreate(
                ['title' => $oer['title']],
                $oer
            );
        }
    }
}
