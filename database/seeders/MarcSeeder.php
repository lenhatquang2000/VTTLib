<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BibliographicRecord;
use App\Models\MarcField;
use App\Models\MarcSubfield;

class MarcSeeder extends Seeder
{
    public function run(): void
    {
        $record = BibliographicRecord::create([
            'leader' => '00000nam a2200000 a 4500',
            'record_type' => 'book',
        ]);

        $fields = [
            ['tag' => '020', 'subfields' => [['code' => 'c', 'value' => '250.000 VNĐ'], ['code' => 'd', 'value' => 'Bản in phổ thông']]],
            ['tag' => '041', 'subfields' => [['code' => 'a', 'value' => 'vie']]],
            ['tag' => '082', 'subfields' => [['code' => 'a', 'value' => '005.133'], ['code' => 'b', 'value' => 'Phòng kỹ thuật'], ['code' => '2', 'value' => '23']]],
            ['tag' => '100', 'subfields' => [['code' => 'a', 'value' => 'Nguyễn Văn A']]],
            ['tag' => '150', 'subfields' => [['code' => 'a', 'value' => 'Lập trình PHP']]],
            ['tag' => '245', 'subfields' => [['code' => 'a', 'value' => 'Lập trình Laravel từ cơ bản đến nâng cao'], ['code' => 'b', 'value' => 'Giáo trình đào tạo nội bộ'], ['code' => 'c', 'value' => 'Nguyễn Văn A; Trần Văn B']]],
            ['tag' => '250', 'subfields' => [['code' => 'a', 'value' => 'Tái bản lần thứ 2']]],
            ['tag' => '260', 'subfields' => [['code' => 'a', 'value' => 'Hà Nội'], ['code' => 'b', 'value' => 'NXB Công Nghệ'], ['code' => 'c', 'value' => '2025']]],
            ['tag' => '300', 'subfields' => [['code' => 'a', 'value' => '350 trang'], ['code' => 'b', 'value' => 'Minh họa màu'], ['code' => 'c', 'value' => '24cm'], ['code' => 'e', 'value' => 'Kèm CD bài tập']]],
            ['tag' => '650', 'subfields' => [['code' => 'a', 'value' => 'Phát triển Web']]],
            ['tag' => '852', 'subfields' => [['code' => 'j', 'value' => 'VTTU'], ['code' => '1', 'value' => 'VN'], ['code' => 'b', 'value' => 'Kho chính'], ['code' => 'c', 'value' => 'Giá 01-A']]],
            ['tag' => '856', 'subfields' => [['code' => 'u', 'value' => 'https://vttlib.com/ebook/laravel-basic']]],
            ['tag' => '900', 'subfields' => [['code' => 'a', 'value' => 'Mới nhập tháng 1/2026']]],
            ['tag' => '911', 'subfields' => [['code' => 'a', 'value' => 'Trung tâm Công nghệ phần mềm']]],
            ['tag' => '920', 'subfields' => [['code' => 'a', 'value' => 'Nguyễn Văn C (Biên tập)']]],
            ['tag' => '925', 'subfields' => [['code' => 'a', 'value' => 'Giấy in']]],
            ['tag' => '926', 'subfields' => [['code' => 'a', 'value' => 'Bình thường']]],
            ['tag' => '930', 'subfields' => [['code' => 'a', 'value' => 'DEP-2026-001']]],
            ['tag' => '933', 'subfields' => [['code' => 'a', 'value' => 'HOT_BOOK']]],
            ['tag' => '940', 'subfields' => [['code' => 'a', 'value' => 'Area 51 Level']]],
            ['tag' => '941', 'subfields' => [['code' => 'a', 'value' => 'Danh mục kỹ thuật']]],
        ];

        foreach ($fields as $index => $fieldData) {
            $field = MarcField::create([
                'record_id' => $record->id,
                'tag' => $fieldData['tag'],
                'sequence' => $index,
            ]);

            foreach ($fieldData['subfields'] as $subfieldData) {
                MarcSubfield::create([
                    'marc_field_id' => $field->id,
                    'code' => $subfieldData['code'],
                    'value' => $subfieldData['value'],
                ]);
            }
        }
    }
}
