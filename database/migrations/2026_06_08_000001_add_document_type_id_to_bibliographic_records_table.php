<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->foreignId('document_type_id')->nullable()->after('framework')->constrained('document_types')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bibliographic_records', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['document_type_id']);
            $table->dropColumn('document_type_id');
        });
    }
};
