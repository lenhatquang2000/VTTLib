<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\StorageLocation;
use App\Models\DocumentType;

class OldSystemAuxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Branch (LIBMAIN)
        $branch = Branch::updateOrCreate(
            ['code' => 'LIBMAIN'],
            [
                'name' => 'TRƯỜNG ĐẠI HỌC VÕ TRƯỜNG TOẢN',
                'address' => 'Quốc Lộ 1A, xã Thạnh Xuân, Châu Thành A, Hậu Giang',
                'phone' => '02933953222',
                'is_active' => true,
            ]
        );

        // 2. Seed Storage Locations (KGT, KTLTK, LVKM)
        $locations = [
            [
                'code' => 'KGT',
                'name' => 'Kho Giáo trình',
                'description' => 'Dùng lưu giáo trình học tập',
                'is_active' => true,
            ],
            [
                'code' => 'KTLTK',
                'name' => 'Kho tài liệu tham khảo',
                'description' => 'Dùng lưu sách tham khảo',
                'is_active' => true,
            ],
            [
                'code' => 'LVKM',
                'name' => 'Thư viện Đại học Võ Trường Toản',
                'description' => 'Kho mượn trả chính',
                'is_active' => true,
            ],
        ];

        foreach ($locations as $loc) {
            StorageLocation::updateOrCreate(
                ['code' => $loc['code']],
                [
                    'branch_id' => $branch->id,
                    'name' => $loc['name'],
                    'description' => $loc['description'],
                    'is_active' => $loc['is_active'],
                ]
            );
        }

        // 3. Seed Document Types (BAO, BQ, BĐ, CK, DT)
        $docTypes = [
            [
                'code' => 'BAO',
                'name' => 'Nhật báo (Daily newspaper)',
                'marc_type' => 's',
                'description' => 'Báo hàng ngày',
                'default_loan_days' => 1,
                'is_loanable' => false,
                'order' => 10,
            ],
            [
                'code' => 'BQ',
                'name' => 'Báo quyển (Newspaper volume)',
                'marc_type' => 's',
                'description' => 'Báo đóng quyển',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 11,
            ],
            [
                'code' => 'BĐ',
                'name' => 'Bản đồ (Map)',
                'marc_type' => 'e',
                'description' => 'Bản đồ',
                'default_loan_days' => 0,
                'is_loanable' => false,
                'order' => 12,
            ],
            [
                'code' => 'CK',
                'name' => 'Chuyên khảo (Monograph)',
                'marc_type' => 'a',
                'description' => 'Chuyên khảo',
                'default_loan_days' => 14,
                'is_loanable' => true,
                'order' => 13,
            ],
            [
                'code' => 'DT',
                'name' => 'Đề tài nghiên cứu',
                'marc_type' => 'a',
                'description' => 'Đề tài nghiên cứu khoa học',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 14,
            ],
        ];

        foreach ($docTypes as $type) {
            DocumentType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'marc_type' => $type['marc_type'],
                    'description' => $type['description'],
                    'default_loan_days' => $type['default_loan_days'],
                    'is_loanable' => $type['is_loanable'],
                    'is_active' => true,
                    'order' => $type['order'],
                ]
            );
        }
    }
}
