<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryEntry;
use App\Models\WebsiteAccess;
use App\Models\PatronDetail;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Get existing data
        $patrons = PatronDetail::limit(5)->get();
        $branches = Branch::limit(3)->get();
        $users = User::limit(3)->get();

        // Create Library Entries
        for ($i = 0; $i < 50; $i++) {
            LibraryEntry::create([
                'patron_detail_id' => $patrons->random()->id,
                'entry_time' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'exit_time' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->addMinutes(rand(30, 240)),
                'purpose' => ['Mượn sách', 'Đọc tại chỗ', 'Tra cứu tài liệu', 'Làm việc nhóm'][array_rand(['Mượn sách', 'Đọc tại chỗ', 'Tra cứu tài liệu', 'Làm việc nhóm'])],
                'entry_type' => 'physical',
                'branch_id' => $branches->random()->id,
                'notes' => 'Test data'
            ]);
        }

        // Create Website Accesses
        for ($i = 0; $i < 100; $i++) {
            WebsiteAccess::create([
                'access_time' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'page_url' => ['/home', '/catalog', '/patrons', '/circulation'][array_rand(['/home', '/catalog', '/patrons', '/circulation'])],
                'session_id' => 'session_' . uniqid(),
                'user_id' => rand(0, 1) ? $users->random()->id : null,
                'access_type' => ['visit', 'search', 'download', 'login'][array_rand(['visit', 'search', 'download', 'login'])],
                'device_type' => ['desktop', 'mobile', 'tablet'][array_rand(['desktop', 'mobile', 'tablet'])],
                'browser' => ['Chrome', 'Firefox', 'Safari', 'Edge'][array_rand(['Chrome', 'Firefox', 'Safari', 'Edge'])],
                'platform' => 'Windows',
                'referrer' => rand(0, 1) ? 'https://google.com' : null
            ]);
        }

        $this->command->info('Test data created successfully!');
    }
}
