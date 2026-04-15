<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('digital_categories')) {
            return;
        }

        $now = now();

        $defaults = [
            ['code' => 'BAI_GIANG_VTTU', 'name' => 'Bài giảng VTTU'],
            ['code' => 'Y_HOC_SUC_KHOE', 'name' => 'Y học – Sức khỏe'],
            ['code' => 'KINH_TE_LUAT', 'name' => 'Kinh tế – Luật'],
            ['code' => 'NGOAI_NGU_TIN_HOC', 'name' => 'Ngoại ngữ – Tin học'],
            ['code' => 'CHINH_TRI_XA_HOI', 'name' => 'Chính trị – Xã hội'],
            ['code' => 'KHOA_LUAN_TOT_NGHIEP', 'name' => 'Khóa luận tốt nghiệp'],
        ];

        foreach ($defaults as $item) {
            DB::table('digital_categories')->updateOrInsert(
                ['code' => $item['code']],
                ['name' => $item['name'], 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('digital_categories')) {
            return;
        }

        DB::table('digital_categories')->whereIn('code', [
            'BAI_GIANG_VTTU',
            'Y_HOC_SUC_KHOE',
            'KINH_TE_LUAT',
            'NGOAI_NGU_TIN_HOC',
            'CHINH_TRI_XA_HOI',
            'KHOA_LUAN_TOT_NGHIEP',
        ])->delete();
    }
};
