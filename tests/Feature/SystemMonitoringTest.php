<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SystemMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed system settings needed for views
        DB::table('system_settings')->insert([
            ['key' => 'site_logo', 'value' => 'logo.png', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'library_name_vi', 'value' => 'Thư viện', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'library_name_en', 'value' => 'Library', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Test auth events (login, logout, failed login) log correctly.
     */
    public function test_auth_events_are_logged(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'name' => 'Test User',
            'password' => bcrypt('password123'),
        ]);

        // Trigger Login Event
        event(new \Illuminate\Auth\Events\Login('web', $user, false));

        // Assert log was created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'auth_login',
        ]);

        // Trigger Logout Event
        event(new \Illuminate\Auth\Events\Logout('web', $user));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'auth_logout',
        ]);

        // Trigger Failed Event
        event(new \Illuminate\Auth\Events\Failed('web', null, ['username' => 'wronguser']));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => null,
            'action' => 'auth_failed',
        ]);
    }

    /**
     * Test authorization for monitoring routes.
     */
    public function test_monitoring_routes_require_authentication_and_admin_role(): void
    {
        // 1. Guests get redirected to login (handled by auth middleware)
        $response = $this->get('/topsecret/monitoring');
        $response->assertRedirect('/login');

        // 2. Normal visitor users get 403 (EnsureHasRole throws 403)
        $visitor = User::factory()->create();
        $visitorRole = Role::create(['name' => 'visitor', 'display_name' => 'Visitor']);
        $visitor->roles()->attach($visitorRole);

        $response = $this->actingAs($visitor)->get('/topsecret/monitoring');
        $response->assertStatus(403);
    }
}
