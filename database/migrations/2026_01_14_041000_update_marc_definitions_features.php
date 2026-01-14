<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('marc_tag_definitions', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('label');
        });

        Schema::table('marc_subfield_definitions', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('label');
            $table->boolean('is_mandatory')->default(false)->after('is_visible');
            $table->boolean('is_repeatable')->default(false)->after('is_mandatory');
        });
    }

    public function down(): void
    {
        Schema::table('marc_tag_definitions', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });
        Schema::table('marc_subfield_definitions', function (Blueprint $table) {
            $table->dropColumn(['is_visible', 'is_mandatory', 'is_repeatable']);
        });
    }
};
