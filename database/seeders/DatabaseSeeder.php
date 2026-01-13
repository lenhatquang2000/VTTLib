<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Metadata;
use App\Models\MetadataValue;
use App\Models\Book;
use App\Models\Sidebar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên'
        ]);

        $visitorRole = Role::create([
            'name' => 'visitor',
            'display_name' => 'Khách'
        ]);

        $rootRole = Role::create([
            'name' => 'root',
            'display_name' => 'Siêu quản trị'
        ]);

        // 2. Create Root User
        $rootUser = User::factory()->create([
            'name' => 'System Root',
            'email' => 'root@root.com',
            'password' => bcrypt('root123'),
        ]);
        $rootUser->roles()->attach($rootRole);

        // 3. Create Agent User and assign Admin role
        $agent = User::factory()->create([
            'name' => 'Secret Agent',
            'email' => 'agent@vttlib.com',
            'password' => bcrypt('password'),
        ]);
        $agent->roles()->attach($adminRole);

        // 3. Create normal users and assign Visitor role
        User::factory(10)->create()->each(function ($user) use ($visitorRole) {
            $user->roles()->attach($visitorRole);
        });

        // 4. Seed Metadata
        $language = Metadata::create([
            'metadata_code' => 'LANGUAGE',
            'metadata_name' => 'Mã ngôn ngữ',
            'description' => 'Ngôn ngữ của tài liệu',
            'allow_multiple' => true,
        ]);

        $language->values()->createMany([
            ['value_code' => 'vie', 'value_name' => 'Tiếng Việt'],
            ['value_code' => 'eng', 'value_name' => 'Tiếng Anh'],
            ['value_code' => 'fre', 'value_name' => 'Tiếng Pháp'],
        ]);

        $docType = Metadata::create([
            'metadata_code' => 'DOC_TYPE',
            'metadata_name' => 'Loại tài liệu',
            'description' => 'Phân loại hình thức tài liệu',
            'allow_multiple' => false,
        ]);

        $docType->values()->createMany([
            ['value_code' => 'book', 'value_name' => 'Sách'],
            ['value_code' => 'thesis', 'value_name' => 'Luận văn'],
            ['value_code' => 'journal', 'value_name' => 'Tạp chí'],
        ]);

        // 5. Seed sample book
        $book = Book::create([
            'title' => 'Lập trình Laravel căn bản',
            'author' => 'Nguyễn Văn A',
            'publisher' => 'NXB Giáo Dục',
            'year_publish' => '2023',
            'isbn' => '1234567890',
        ]);

        // Link book to metadata values
        $book->metadataValues()->attach([
            MetadataValue::where('value_code', 'vie')->first()->id,
            MetadataValue::where('value_code', 'book')->first()->id,
        ]);

        // 6. Seed Sidebars
        $dashboardTab = Sidebar::create([
            'name' => 'Dashboard',
            'route_name' => 'admin.dashboard',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            'order' => 1
        ]);

        $usersTab = Sidebar::create([
            'name' => 'Users',
            'route_name' => '#',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'order' => 2
        ]);

        $booksTab = Sidebar::create([
            'name' => 'Books',
            'route_name' => '#',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
            'order' => 3
        ]);

        $metadataTab = Sidebar::create([
            'name' => 'Metadata',
            'route_name' => 'admin.metadata.index',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-10 5h10"></path></svg>',
            'order' => 4
        ]);

        $loansTab = Sidebar::create([
            'name' => 'Loans',
            'route_name' => '#',
            'icon' => '<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
            'order' => 5
        ]);

        // Assign default tabs to the specific RoleUser assignment of the agent
        $roleUser = \App\Models\RoleUser::where('user_id', $agent->id)->where('role_id', $adminRole->id)->first();

        $roleUser->sidebars()->createMany([
            ['sidebar_id' => $dashboardTab->id],
            ['sidebar_id' => $usersTab->id],
            ['sidebar_id' => $booksTab->id],
            ['sidebar_id' => $metadataTab->id],
            ['sidebar_id' => $loansTab->id],
        ]);
    }
}
