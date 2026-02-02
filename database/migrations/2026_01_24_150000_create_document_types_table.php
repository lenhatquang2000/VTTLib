<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->string('marc_type', 10)->nullable()->comment('MARC21 Type of Record (Leader/06)');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('default_loan_days')->default(14);
            $table->boolean('is_loanable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Add document_type_id to book_items table if exists
        if (Schema::hasTable('book_items')) {
            Schema::table('book_items', function (Blueprint $table) {
                if (!Schema::hasColumn('book_items', 'document_type_id')) {
                    $table->foreignId('document_type_id')->nullable()->after('bibliographic_record_id')
                        ->constrained('document_types')->nullOnDelete();
                }
            });
        }

        // Seed default document types
        $this->seedDefaultTypes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('book_items') && Schema::hasColumn('book_items', 'document_type_id')) {
            Schema::table('book_items', function (Blueprint $table) {
                $table->dropForeign(['document_type_id']);
                $table->dropColumn('document_type_id');
            });
        }

        Schema::dropIfExists('document_types');
    }

    /**
     * Seed default document types
     */
    private function seedDefaultTypes(): void
    {
        $types = [
            [
                'name' => 'Sách',
                'code' => 'BOOK',
                'marc_type' => 'a',
                'description' => 'Sách in thông thường',
                'icon' => 'book',
                'default_loan_days' => 14,
                'is_loanable' => true,
                'order' => 1,
            ],
            [
                'name' => 'Tạp chí',
                'code' => 'JOURNAL',
                'marc_type' => 's',
                'description' => 'Tạp chí, tập san định kỳ',
                'icon' => 'newspaper',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 2,
            ],
            [
                'name' => 'Báo',
                'code' => 'NEWSPAPER',
                'marc_type' => 's',
                'description' => 'Báo hàng ngày',
                'icon' => 'file-text',
                'default_loan_days' => 1,
                'is_loanable' => false,
                'order' => 3,
            ],
            [
                'name' => 'Luận văn/Luận án',
                'code' => 'THESIS',
                'marc_type' => 'a',
                'description' => 'Luận văn thạc sĩ, luận án tiến sĩ',
                'icon' => 'graduation-cap',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 4,
            ],
            [
                'name' => 'Đề tài nghiên cứu',
                'code' => 'RESEARCH',
                'marc_type' => 'a',
                'description' => 'Đề tài nghiên cứu khoa học',
                'icon' => 'flask',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 5,
            ],
            [
                'name' => 'CD/DVD',
                'code' => 'DISC',
                'marc_type' => 'g',
                'description' => 'Đĩa CD, DVD, Blu-ray',
                'icon' => 'disc',
                'default_loan_days' => 7,
                'is_loanable' => true,
                'order' => 6,
            ],
            [
                'name' => 'Bản đồ',
                'code' => 'MAP',
                'marc_type' => 'e',
                'description' => 'Bản đồ, atlas',
                'icon' => 'map',
                'default_loan_days' => 7,
                'is_loanable' => false,
                'order' => 7,
            ],
            [
                'name' => 'Tài liệu điện tử',
                'code' => 'EBOOK',
                'marc_type' => 'a',
                'description' => 'Sách điện tử, tài liệu số',
                'icon' => 'tablet',
                'default_loan_days' => 30,
                'is_loanable' => true,
                'order' => 8,
            ],
            [
                'name' => 'Tài liệu tham khảo',
                'code' => 'REFERENCE',
                'marc_type' => 'a',
                'description' => 'Từ điển, bách khoa toàn thư (không cho mượn)',
                'icon' => 'bookmark',
                'default_loan_days' => 0,
                'is_loanable' => false,
                'order' => 9,
            ],
            [
                'name' => 'Khác',
                'code' => 'OTHER',
                'marc_type' => null,
                'description' => 'Các loại tài liệu khác',
                'icon' => 'file',
                'default_loan_days' => 14,
                'is_loanable' => true,
                'order' => 99,
            ],
        ];

        foreach ($types as $type) {
            \DB::table('document_types')->insert(array_merge($type, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
