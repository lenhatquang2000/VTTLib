<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DigitalResource;
use App\Models\DigitalFolder;
use Illuminate\Support\Str;

class DigitalResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo folder mẫu nếu chưa có
        $medicalFolder = DigitalFolder::updateOrCreate(['folder_name' => 'Y khoa'], ['folder_code' => 'Y_KHOA']);
        $generalFolder = DigitalFolder::updateOrCreate(['folder_name' => 'Tài liệu chung'], ['folder_code' => 'GENERAL']);

        // Dữ liệu Y khoa
        $medicalData = [
            ['title' => 'Sinh lý học Y khoa', 'author' => ['Mai Phương Thảo'], 'subject' => ['Y sinh', 'Sinh lý học']],
            ['title' => 'Tiếp cận chẩn đoán Nội khoa', 'author' => ['Hoàng Văn Sỹ'], 'subject' => ['Nội khoa', 'Lâm sàng']],
            ['title' => 'Hướng dẫn Sản phụ khoa 2024', 'author' => ['Hoàng Thị Diễm Tuyết'], 'subject' => ['Sản khoa']],
            ['title' => 'Atlas Giải phẫu người', 'author' => ['Frank H. Netter'], 'subject' => ['Giải phẫu']],
        ];

        foreach ($medicalData as $data) {
            DigitalResource::updateOrCreate(
                ['title' => $data['title']],
                [
                    'folder_id' => $medicalFolder->id,
                    'authors' => $data['author'],
                    'subjects' => $data['subject'],
                    'description' => 'Tài liệu chuyên sâu phục vụ nghiên cứu và học tập tại VTTU.',
                    'status' => 'published',
                    'resource_type' => 'PDF',
                    'file_path' => 'uploads/sample.pdf',
                    'file_name' => 'sample.pdf',
                    'language' => 'vi',
                    'view_count' => rand(100, 500),
                    'download_count' => rand(10, 50)
                ]
            );
        }

        // Dữ liệu tài liệu mới khác
        $generalData = [
            ['title' => 'Kỹ thuật lập trình Python nâng cao', 'author' => ['Nguyễn Văn A'], 'subject' => ['CNTT']],
            ['title' => 'Kinh tế vĩ mô hiện đại', 'author' => ['Trần Thị B'], 'subject' => ['Kinh tế']],
            ['title' => 'Quản trị nhân sự trong kỷ nguyên số', 'author' => ['Lê Văn C'], 'subject' => ['Quản trị']],
            ['title' => 'Kỹ thuật xây dựng cầu đường', 'author' => ['Phạm Văn D'], 'subject' => ['Xây dựng']],
        ];

        foreach ($generalData as $data) {
            DigitalResource::updateOrCreate(
                ['title' => $data['title']],
                [
                    'folder_id' => $generalFolder->id,
                    'authors' => $data['author'],
                    'subjects' => $data['subject'],
                    'description' => 'Tài liệu cập nhật mới nhất năm 2024.',
                    'status' => 'published',
                    'resource_type' => 'PDF',
                    'file_path' => 'uploads/sample.pdf',
                    'file_name' => 'sample.pdf',
                    'language' => 'vi',
                    'view_count' => rand(50, 200),
                    'download_count' => rand(5, 20)
                ]
            );
        }
    }
}
