<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_public_registration_screen_is_disabled(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_public_registration_submission_is_disabled(): void
    {
        $response = $this->post('/register', [
            'name' => 'Public User',
            'email' => 'public@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
        $this->assertGuest();
    }

    public function test_admin_can_create_new_staff_account(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'New Staff Member',
            'email' => 'newstaff@gctu.edu.gh',
            'password' => 'password123',
            'role' => 'Procurement Officer',
        ]);

        $response->assertRedirect(route('users.manage'));

        $newStaff = User::where('email', 'newstaff@gctu.edu.gh')->first();
        $this->assertNotNull($newStaff);
        $this->assertTrue($newStaff->hasRole('Procurement Officer'));

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $admin->id,
            'target_table' => 'users',
            'target_id' => $newStaff->id,
        ]);
    }

    public function test_non_admin_cannot_create_staff_account(): void
    {
        $nonAdmin = User::factory()->create();
        $nonAdmin->assignRole('viewer');

        $response = $this->actingAs($nonAdmin)->post(route('users.store'), [
            'name' => 'Unauthorized Staff',
            'email' => 'unauth@gctu.edu.gh',
            'password' => 'password123',
            'role' => 'Project Manager',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'unauth@gctu.edu.gh']);
    }

    public function test_created_user_can_login_with_assigned_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'John Manager',
            'email' => 'john.manager@gctu.edu.gh',
            'password' => 'tempPass123!',
            'role' => 'Project Manager',
        ]);

        // Logout admin
        $this->post('/logout');

        // Log in as new user
        $loginResponse = $this->post('/login', [
            'email' => 'john.manager@gctu.edu.gh',
            'password' => 'tempPass123!',
        ]);

        $loginResponse->assertRedirect(route('dashboard', absolute: false));
        $createdUser = User::where('email', 'john.manager@gctu.edu.gh')->first();
        $this->assertAuthenticatedAs($createdUser);
        $this->assertTrue($createdUser->hasRole('Project Manager'));
    }
}
