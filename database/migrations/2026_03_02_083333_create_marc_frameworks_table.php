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
        Schema::create('marc_frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('marc_tag_definitions', function (Blueprint $table) {
            $table->unsignedBigInteger('framework_id')->nullable()->after('id');
            $table->foreign('framework_id')->references('id')->on('marc_frameworks')->onDelete('cascade');
            
            // Re-define uniqueness to tag + framework
            $table->dropUnique(['tag']);
            $table->unique(['framework_id', 'tag']);
        });

        Schema::table('marc_subfield_definitions', function (Blueprint $table) {
             // Subfields already depend on tag, but we might want them linked to the framework too if we reuse tags across frameworks?
             // No, TagDefinition itself is framework-specific now.
             // So we just need to ensure subfields are unique for the tag.
        });
    }

    public function down(): void
    {
        Schema::table('marc_tag_definitions', function (Blueprint $table) {
            $table->dropUnique(['framework_id', 'tag']);
            $table->unique(['tag']);
            $table->dropForeign(['framework_id']);
            $table->dropColumn('framework_id');
        });
        Schema::dropIfExists('marc_frameworks');
    }
};
