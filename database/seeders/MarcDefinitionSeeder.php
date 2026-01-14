<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            '020' => [
                'label' => 'CHỈ SỐ ISBN',
                'subfields' => [
                    'c' => 'Điều kiện mua (Giá)',
                    'd' => 'Phân phối / Số lượng'
                ]
            ],
            '041' => [
                'label' => 'MÃ NGÔN NGỮ',
                'subfields' => [
                    'a' => 'Mã ngôn ngữ văn bản'
                ]
            ],
            '082' => [
                'label' => 'CHỈ SỐ PHÂN LOẠI THẬP PHÂN DEWEY (DDC)',
                'subfields' => [
                    'a' => 'Chỉ số phân loại (DDC)',
                    'b' => 'Chỉ số ấn phẩm',
                    '2' => 'Chỉ số phiên bản Dewey'
                ]
            ],
            '100' => [
                'label' => 'TIÊU ĐỀ MÔ TẢ CHÍNH - TÁC GIẢ CÁ NHÂN',
                'subfields' => [
                    'a' => 'Họ và tên riêng',
                    'd' => 'Ngày tháng (sinh/mất)'
                ]
            ],
            '150' => [
                'label' => 'CHỦ ĐỀ',
                'subfields' => [
                    'a' => 'Thuật ngữ chủ đề'
                ]
            ],
            '245' => [
                'label' => 'NHAN ĐỀ VÀ THÔNG TIN TRÁCH NHIỆM',
                'subfields' => [
                    'a' => 'Nhan đề chính',
                    'b' => 'Phụ đề / Nhan đề song song',
                    'c' => 'Thông tin trách nhiệm (Tác giả, biên tập...)'
                ]
            ],
            '250' => [
                'label' => 'THÔNG TIN VỀ LẦN XUẤT BẢN',
                'subfields' => [
                    'a' => 'Lần xuất bản'
                ]
            ],
            '260' => [
                'label' => 'THÔNG TIN VỀ XUẤT BẢN, PHÁT HÀNH',
                'subfields' => [
                    'a' => 'Nơi xuất bản',
                    'b' => 'Nhà xuất bản',
                    'c' => 'Ngày xuất bản'
                ]
            ],
            '300' => [
                'label' => 'MÔ TẢ VẬT LÝ',
                'subfields' => [
                    'a' => 'Số trang / Khối lượng',
                    'b' => 'Đặc điểm vật lý khác',
                    'c' => 'Khổ sách',
                    'e' => 'Tài liệu đi kèm'
                ]
            ],
            '650' => [
                'label' => 'CHUYÊN NGÀNH',
                'subfields' => [
                    'a' => 'Thuật ngữ chuyên ngành'
                ]
            ],
            '852' => [
                'label' => 'VỊ TRÍ/SỐ BÁO DANH',
                'subfields' => [
                    'j' => 'VTTU code',
                    '1' => 'Mã quốc gia',
                    'b' => 'Vị trí cụ thể',
                    'c' => 'Vị trí xếp giá'
                ]
            ],
            '856' => [
                'label' => 'TƯ LIỆU ĐÍNH KÈM (URL)',
                'subfields' => [
                    'u' => 'Đường dẫn URL'
                ]
            ],
            '911' => [
                'label' => 'NGƯỜI NHẬP TIN',
                'subfields' => [
                    'a' => 'Tên người nhập tin'
                ]
            ],
            '926' => [
                'label' => 'MỨC ĐỘ MẬT',
                'subfields' => [
                    'a' => 'Độ mật'
                ]
            ]
        ];

        foreach ($definitions as $tag => $data) {
            DB::table('marc_tag_definitions')->insert([
                'tag' => $tag,
                'label' => $data['label'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($data['subfields'] as $code => $subLabel) {
                DB::table('marc_subfield_definitions')->insert([
                    'tag' => $tag,
                    'code' => $code,
                    'label' => $subLabel,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
