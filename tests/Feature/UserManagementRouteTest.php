<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_guests_are_redirected_from_user_management(): void
    {
        $response = $this->get('/users/manage');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_user_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $response = $this->actingAs($user)->get('/users/manage');
        $response->assertStatus(403);
    }

    public function test_admin_users_can_access_user_management(): void
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $response = $this->actingAs($adminUser)->get('/users/manage');
        $response->assertStatus(200);
        $response->assertSee('Registered Institutional Accounts');
    }

    public function test_administrator_users_can_access_user_management(): void
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('Administrator');

        $response = $this->actingAs($adminUser)->get('/users/manage');
        $response->assertStatus(200);
    }
}
