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
        \App\Models\SiteTemplate::updateOrCreate(
            ['template_code' => 'opac'],
            [
                'template_name' => 'Tra cứu OPAC (Modern)',
                'sort_order' => 15,
                'is_active' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\SiteTemplate::where('template_code', 'opac')->delete();
    }
};
