<?php

namespace Database\Seeders;

use App\Models\MarcFramework;
use App\Models\MarcTagDefinition;
use App\Models\MarcSubfieldDefinition;
use Illuminate\Database\Seeder;

class MarcFrameworkSeeder extends Seeder
{
    public function run(): void
    {
        $framework = MarcFramework::updateOrCreate(
            ['code' => 'STANDARD'],
            [
                'name' => 'Khung biên mục chuẩn MARC21',
                'description' => 'Khung biên mục đầy đủ theo yêu cầu hệ thống (VTTLib Standard)',
                'is_active' => true,
            ]
        );

        // Clear existing tags for this framework before re-seeding
        $framework->tags()->detach();

        $tags = [
            // (1) Nhóm định danh và kiểm soát
            ['tag' => '020', 'label' => 'ISBN', 'description' => 'Thông tin nhận diện ấn phẩm, điều kiện mua và phân phối', 'subfields' => [
                ['code' => 'a', 'label' => 'Số ISBN'],
                ['code' => 'c', 'label' => 'Điều kiện mua/giá tiền'],
            ]],
            ['tag' => '041', 'label' => 'Mã ngôn ngữ', 'description' => 'Xác định ngôn ngữ nội dung tài liệu', 'subfields' => [
                ['code' => 'a', 'label' => 'Mã ngôn ngữ văn bản'],
            ]],

            // (2) Nhóm phân loại và chủ đề
            ['tag' => '082', 'label' => 'Chỉ số phân loại DDC', 'description' => 'Phục vụ xếp giá, lưu thông và tra cứu', 'subfields' => [
                ['code' => 'a', 'label' => 'Chỉ số phân loại'],
                ['code' => '2', 'label' => 'Số lần xuất bản của DDC'],
            ]],
            ['tag' => '150', 'label' => 'Chủ đề', 'description' => 'Thuật ngữ mô tả nội dung', 'subfields' => [
                ['code' => 'a', 'label' => 'Chủ đề'],
            ]],
            ['tag' => '650', 'label' => 'Chuyên ngành', 'description' => 'Phân loại theo lĩnh vực đào tạo', 'subfields' => [
                ['code' => 'a', 'label' => 'Thuật ngữ chuyên ngành'],
            ]],

            // (3) Nhóm mô tả thư mục
            ['tag' => '100', 'label' => 'Tác giả cá nhân', 'description' => 'Tác giả chính của tài liệu', 'subfields' => [
                ['code' => 'a', 'label' => 'Tên tác giả'],
            ]],
            ['tag' => '245', 'label' => 'Nhan đề và thông tin trách nhiệm', 'description' => 'Nhan đề chính, phụ và thông tin chịu trách nhiệm', 'subfields' => [
                ['code' => 'a', 'label' => 'Nhan đề chính'],
                ['code' => 'b', 'label' => 'Nhan đề phụ/Thông tin khác'],
                ['code' => 'c', 'label' => 'Thông tin trách nhiệm'],
            ]],
            ['tag' => '250', 'label' => 'Lần xuất bản', 'description' => 'Thông tin về lần tái bản/xuất bản', 'subfields' => [
                ['code' => 'a', 'label' => 'Số lần xuất bản'],
            ]],
            ['tag' => '260', 'label' => 'Thông tin xuất bản, phát hành', 'description' => 'Nơi xuất bản, nhà xuất bản, năm xuất bản', 'subfields' => [
                ['code' => 'a', 'label' => 'Nơi xuất bản'],
                ['code' => 'b', 'label' => 'Nhà xuất bản'],
                ['code' => 'c', 'label' => 'Năm xuất bản'],
            ]],
            ['tag' => '300', 'label' => 'Mô tả vật lý', 'description' => 'Số trang, đặc điểm vật lý, khổ, tư liệu kèm theo', 'subfields' => [
                ['code' => 'a', 'label' => 'Số trang'],
                ['code' => 'c', 'label' => 'Khổ/Kích thước'],
                ['code' => 'e', 'label' => 'Tài liệu kèm theo'],
            ]],

            // (4) Nhóm vị trí lưu trữ và khai thác
            ['tag' => '852', 'label' => 'Vị trí/số báo danh', 'description' => 'Ký hiệu thư viện, số quản lý, vị trí xếp giá', 'subfields' => [
                ['code' => 'a', 'label' => 'Ký hiệu thư viện'],
                ['code' => 'c', 'label' => 'Vị trí xếp giá'],
            ]],
            ['tag' => '856', 'label' => 'Tư liệu đính kèm', 'description' => 'Liên kết tài liệu số (URL)', 'subfields' => [
                ['code' => 'u', 'label' => 'Link URL'],
            ]],

            // (5) Nhóm trường nghiệp vụ và hệ thống
            ['tag' => '900', 'label' => 'Biểu ghi ấn phẩm mới', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Dấu hiệu ấn phẩm mới'],
            ]],
            ['tag' => '911', 'label' => 'Người nhập tin', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Tên người nhập'],
            ]],
            ['tag' => '920', 'label' => 'Tác giả bổ sung', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Tên tác giả bổ sung'],
            ]],
            ['tag' => '925', 'label' => 'Vật mang tin', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Loại vật mang tin'],
            ]],
            ['tag' => '926', 'label' => 'Mức độ mật', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Cấp độ mật'],
            ]],
            ['tag' => '930', 'label' => 'Deposit (lưu chiểu)', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Thông tin lưu chiểu'],
            ]],
            ['tag' => '933', 'label' => 'Dấu hiệu tài liệu mới', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Trạng thái tài liệu mới'],
            ]],
            ['tag' => '940', 'label' => 'Văn bản pháp lý', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Số văn bản'],
            ]],
            ['tag' => '941', 'label' => 'Danh mục tài liệu', 'description' => '', 'subfields' => [
                ['code' => 'a', 'label' => 'Tên danh mục'],
            ]],
        ];

        $i = 0;
        foreach ($tags as $tagData) {
            $tag = MarcTagDefinition::updateOrCreate(
                ['tag' => $tagData['tag'], 'label' => $tagData['label']],
                ['description' => $tagData['description']]
            );

            $framework->tags()->attach($tag->id, [
                'is_visible' => true,
                'order' => $i++
            ]);

            foreach ($tagData['subfields'] as $subfieldData) {
                MarcSubfieldDefinition::updateOrCreate(
                    ['tag_id' => $tag->id, 'code' => $subfieldData['code']],
                    [
                        'label' => $subfieldData['label'],
                        'is_visible' => true,
                        'is_mandatory' => false,
                        'is_repeatable' => false,
                    ]
                );
            }
        }
    }
}
