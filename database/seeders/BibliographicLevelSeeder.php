<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BibliographicLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['code' => 'a', 'name_en' => 'Language material', 'name_vi' => 'Tài liệu văn bản', 'order' => 1],
            ['code' => 'c', 'name_en' => 'Notated music', 'name_vi' => 'Bản nhạc in', 'order' => 2],
            ['code' => 'd', 'name_en' => 'Manuscript notated music', 'name_vi' => 'Bản nhạc chép tay', 'order' => 3],
            ['code' => 'e', 'name_en' => 'Cartographic material', 'name_vi' => 'Bản đồ in', 'order' => 4],
            ['code' => 'f', 'name_en' => 'Manuscript cartographic material', 'name_vi' => 'Bản đồ vẽ tay', 'order' => 5],
            ['code' => 'g', 'name_en' => 'Projected medium', 'name_vi' => 'Các tư liệu chiếu', 'order' => 6],
            ['code' => 'i', 'name_en' => 'Nonmusical sound recording', 'name_vi' => 'Ghi âm không thuộc âm nhạc', 'order' => 7],
            ['code' => 'j', 'name_en' => 'Musical sound recording', 'name_vi' => 'Ghi âm thuộc âm nhạc', 'order' => 8],
            ['code' => 'k', 'name_en' => 'Two-dimensional nonprojectable graphic', 'name_vi' => 'Đồ họa phẳng', 'order' => 9],
            ['code' => 'm', 'name_en' => 'Computer file', 'name_vi' => 'Tập tin máy tính', 'order' => 10],
            ['code' => 'o', 'name_en' => 'Kit', 'name_vi' => 'Bộ tài liệu', 'order' => 11],
            ['code' => 'p', 'name_en' => 'Mixed material', 'name_vi' => 'Tài liệu hỗn hợp', 'order' => 12],
            ['code' => 'r', 'name_en' => '3-D object', 'name_vi' => 'Đồ vật 3 chiều', 'order' => 13],
            ['code' => 't', 'name_en' => 'Manuscript language material', 'name_vi' => 'Tài liệu viết tay', 'order' => 14],
        ];

        foreach ($levels as $level) {
            DB::table('bibliographic_levels')->insert(array_merge($level, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
