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
        Schema::table('marc_subfield_definitions', function (Blueprint $table) {
            $table->dropUnique(['tag', 'code']);
            $table->dropColumn('tag');
            $table->unsignedBigInteger('tag_id')->nullable()->after('id');
            $table->foreign('tag_id')->references('id')->on('marc_tag_definitions')->onDelete('cascade');
            $table->unique(['tag_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('marc_subfield_definitions', function (Blueprint $table) {
            $table->dropUnique(['tag_id', 'code']);
            $table->dropForeign(['tag_id']);
            $table->dropColumn('tag_id');
            $table->string('tag', 3)->after('id');
            $table->unique(['tag', 'code']);
        });
    }
};
