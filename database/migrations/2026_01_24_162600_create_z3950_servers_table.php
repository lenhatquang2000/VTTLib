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
        Schema::create('z3950_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->integer('port')->default(210);
            $table->string('database_name');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('charset')->default('UTF-8');
            $table->string('record_syntax')->default('USMARC');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('use_ssl')->default(false);
            $table->integer('timeout')->default(30);
            $table->integer('max_records')->default(100);
            $table->integer('order')->default(0);
            $table->timestamp('last_connected_at')->nullable();
            $table->enum('last_status', ['success', 'failed', 'unknown'])->default('unknown');
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        // Seed default Z39.50 servers
        $this->seedDefaultServers();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('z3950_servers');
    }

    private function seedDefaultServers(): void
    {
        $servers = [
            [
                'name' => 'Library of Congress',
                'host' => 'z3950.loc.gov',
                'port' => 7090,
                'database_name' => 'VOYAGER',
                'charset' => 'UTF-8',
                'record_syntax' => 'USMARC',
                'description' => 'Thư viện Quốc hội Hoa Kỳ - Nguồn biên mục chuẩn quốc tế',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Thư viện Quốc gia Việt Nam',
                'host' => 'z3950.nlv.gov.vn',
                'port' => 210,
                'database_name' => 'INNOPAC',
                'charset' => 'UTF-8',
                'record_syntax' => 'USMARC',
                'description' => 'Thư viện Quốc gia Việt Nam - Nguồn biên mục tiếng Việt',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'OCLC WorldCat',
                'host' => 'zcat.oclc.org',
                'port' => 210,
                'database_name' => 'OLUCWorldCat',
                'charset' => 'UTF-8',
                'record_syntax' => 'USMARC',
                'description' => 'OCLC WorldCat - Cơ sở dữ liệu thư mục lớn nhất thế giới (cần đăng ký)',
                'is_active' => false,
                'order' => 3,
            ],
        ];

        foreach ($servers as $server) {
            \DB::table('z3950_servers')->insert(array_merge($server, [
                'timeout' => 30,
                'max_records' => 100,
                'use_ssl' => false,
                'last_status' => 'unknown',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
