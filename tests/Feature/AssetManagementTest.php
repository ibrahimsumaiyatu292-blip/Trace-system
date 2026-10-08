<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_authenticated_user_can_view_assets_index_and_show(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $asset = Asset::create([
            'name' => 'Projector HD',
            'category' => 'Electronics',
            'value' => 1200.50,
            'assigned_project' => 'Lecture Hall Upgrade',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->get(route('assets.index'));
        $response->assertStatus(200);
        $response->assertSee('Projector HD');

        $responseShow = $this->actingAs($user)->get(route('assets.show', $asset));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Projector HD');
    }

    public function test_user_without_create_permission_cannot_access_create_or_store(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $responseCreate = $this->actingAs($user)->get(route('assets.create'));
        $responseCreate->assertStatus(403);

        $responseStore = $this->actingAs($user)->post(route('assets.store'), [
            'name' => 'Server Rack',
            'category' => 'IT Infrastructure',
            'value' => 5000.00,
            'assigned_project' => 'Data Center',
            'status' => 'Available',
        ]);
        $responseStore->assertStatus(403);
    }

    public function test_user_with_create_permission_can_create_asset(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Procurement Officer'); // Has create_asset permission

        $response = $this->actingAs($user)->post(route('assets.store'), [
            'name' => 'Dell Workstation',
            'category' => 'Hardware',
            'value' => 2500.00,
            'assigned_project' => 'AI Lab',
            'status' => 'Available',
        ]);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', [
            'name' => 'Dell Workstation',
            'category' => 'Hardware',
            'value' => 2500.00,
        ]);
    }

    public function test_user_with_edit_permission_can_update_asset(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Procurement Officer');

        $asset = Asset::create([
            'name' => 'Old Laptop',
            'category' => 'Computers',
            'value' => 800.00,
            'assigned_project' => 'Library',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->put(route('assets.update', $asset), [
            'name' => 'Updated Laptop Core i7',
            'category' => 'Computers',
            'value' => 950.00,
            'assigned_project' => 'Library',
            'status' => 'In Use',
        ]);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Laptop Core i7',
            'status' => 'In Use',
        ]);
    }

    public function test_user_without_delete_permission_cannot_delete_asset(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Procurement Officer'); // Does NOT have delete_asset

        $asset = Asset::create([
            'name' => 'Office Chair',
            'category' => 'Furniture',
            'value' => 150.00,
            'assigned_project' => 'Admin Office',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->delete(route('assets.destroy', $asset));
        $response->assertStatus(403);
    }

    public function test_administrator_with_delete_permission_can_delete_asset(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin'); // Has delete_asset

        $asset = Asset::create([
            'name' => 'Broken Printer',
            'category' => 'Peripherals',
            'value' => 50.00,
            'assigned_project' => 'Admin Office',
            'status' => 'Decommissioned',
        ]);

        $response = $this->actingAs($admin)->delete(route('assets.destroy', $asset));
        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }
}
