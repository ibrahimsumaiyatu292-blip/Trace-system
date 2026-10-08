<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_successful_login_writes_access_log(): void
    {
        $user = User::factory()->create([
            'email' => 'staff1@gctu.edu.gh',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'staff1@gctu.edu.gh',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'target_table' => 'users',
            'target_id' => $user->id,
        ]);
    }

    public function test_failed_login_writes_access_log_with_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'intruder@gctu.edu.gh',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();

        $this->assertDatabaseHas('access_logs', [
            'target_table' => 'users',
            'action' => 'Failed login attempt for email: intruder@gctu.edu.gh',
        ]);
    }

    public function test_logout_writes_access_log(): void
    {
        $user = User::factory()->create([
            'email' => 'logoutstaff@gctu.edu.gh',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'target_table' => 'users',
            'action' => 'User logged out for email: logoutstaff@gctu.edu.gh',
        ]);
    }
}
