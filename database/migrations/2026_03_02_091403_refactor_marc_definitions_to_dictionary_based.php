<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pivot table for Framework <-> Tag
        if (!Schema::hasTable('marc_framework_tags')) {
            Schema::create('marc_framework_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('framework_id')->constrained('marc_frameworks')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('marc_tag_definitions')->onDelete('cascade');
                $table->boolean('is_visible')->default(true);
                $table->integer('order')->default(0);
                $table->timestamps();
                
                $table->unique(['framework_id', 'tag_id']);
            });
        }

        // 2. Migrate existing data from Tags to the pivot table
        if (Schema::hasColumn('marc_tag_definitions', 'framework_id')) {
            $tags = DB::table('marc_tag_definitions')->get();
            foreach ($tags as $tag) {
                if ($tag->framework_id && DB::table('marc_frameworks')->where('id', $tag->framework_id)->exists()) {
                    DB::table('marc_framework_tags')->insertOrIgnore([
                        'framework_id' => $tag->framework_id,
                        'tag_id' => $tag->id,
                        'is_visible' => $tag->is_visible ?? true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }


        // 3. Remove framework_id and is_visible from tag_definitions (making it global)
        if (Schema::hasColumn('marc_tag_definitions', 'framework_id')) {
            Schema::table('marc_tag_definitions', function (Blueprint $table) {
                // Drop the unique index first
                try {
                    $table->dropUnique('marc_tag_definitions_framework_id_tag_unique');
                } catch (\Exception $e) {}
                
                // Then drop the column
                $table->dropColumn('framework_id');
            });
        }
        
        if (Schema::hasColumn('marc_tag_definitions', 'is_visible')) {
            Schema::table('marc_tag_definitions', function (Blueprint $table) {
                $table->dropColumn('is_visible');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marc_tag_definitions', function (Blueprint $table) {
            $table->foreignId('framework_id')->nullable()->constrained('marc_frameworks')->onDelete('cascade');
            $table->boolean('is_visible')->default(true);
        });

        // Try to recover data from pivot
        if (Schema::hasTable('marc_framework_tags')) {
            $pivotData = DB::table('marc_framework_tags')->get();
            foreach ($pivotData as $data) {
                DB::table('marc_tag_definitions')
                    ->where('id', $data->tag_id)
                    ->update([
                        'framework_id' => $data->framework_id,
                        'is_visible' => $data->is_visible
                    ]);
            }
        }

        Schema::dropIfExists('marc_framework_tags');
    }
};
