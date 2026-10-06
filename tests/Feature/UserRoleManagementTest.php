<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $targetUser = User::factory()->create();
        $targetUser->assignRole('viewer');

        $response = $this->actingAs($admin)->put(route('users.update-role', $targetUser), [
            'role' => 'Procurement Officer',
        ]);

        $response->assertRedirect(route('users.manage'));
        $this->assertTrue($targetUser->fresh()->hasRole('Procurement Officer'));

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $admin->id,
            'target_table' => 'users',
            'target_id' => $targetUser->id,
        ]);
    }

    public function test_non_admin_cannot_update_user_role(): void
    {
        $nonAdmin = User::factory()->create();
        $nonAdmin->assignRole('viewer');

        $targetUser = User::factory()->create();
        $targetUser->assignRole('viewer');

        $response = $this->actingAs($nonAdmin)->put(route('users.update-role', $targetUser), [
            'role' => 'Administrator',
        ]);

        $response->assertStatus(403);
        $this->assertFalse($targetUser->fresh()->hasRole('Administrator'));
    }
}
