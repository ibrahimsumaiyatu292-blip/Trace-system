<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Procurement;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcurementManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_authenticated_user_can_view_procurements(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $procurement = Procurement::create([
            'project_name' => 'Campus Fiber Optic Expansion',
            'item_description' => '500m Single Mode Fiber Cable',
            'amount' => 3500.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('procurements.index'));
        $response->assertStatus(200);
        $response->assertSee('Campus Fiber Optic Expansion');

        $responseShow = $this->actingAs($user)->get(route('procurements.show', $procurement));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('500m Single Mode Fiber Cable');
    }

    public function test_unauthorized_user_cannot_create_procurement(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $response = $this->actingAs($user)->get(route('procurements.create'));
        $response->assertStatus(403);

        $responseStore = $this->actingAs($user)->post(route('procurements.store'), [
            'project_name' => 'Unauthorized Project',
            'item_description' => 'Test item',
            'amount' => 100.00,
            'status' => 'pending',
        ]);
        $responseStore->assertStatus(403);
    }

    public function test_authorized_user_can_create_procurement_linked_to_asset(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Procurement Officer'); // Has create_asset permission

        $asset = Asset::create([
            'name' => 'Cisco Router 4000',
            'category' => 'Networking',
            'value' => 4500.00,
            'assigned_project' => 'Network Backbone',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->post(route('procurements.store'), [
            'project_name' => 'Network Backbone',
            'item_description' => 'Router Maintenance Unit',
            'amount' => 1200.00,
            'status' => 'approved',
            'linked_asset_id' => $asset->id,
        ]);

        $response->assertRedirect(route('procurements.index'));
        $this->assertDatabaseHas('procurements', [
            'project_name' => 'Network Backbone',
            'amount' => 1200.00,
            'status' => 'approved',
            'linked_asset_id' => $asset->id,
        ]);
    }

    public function test_authorized_user_can_update_procurement(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Procurement Officer');

        $procurement = Procurement::create([
            'project_name' => 'Library Computers',
            'item_description' => '10 PC Desktops',
            'amount' => 8000.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->put(route('procurements.update', $procurement), [
            'project_name' => 'Library Computers',
            'item_description' => '10 PC Desktops with Monitors',
            'amount' => 9500.00,
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('procurements.index'));
        $this->assertDatabaseHas('procurements', [
            'id' => $procurement->id,
            'amount' => 9500.00,
            'status' => 'completed',
        ]);
    }

    public function test_admin_can_delete_procurement(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $procurement = Procurement::create([
            'project_name' => 'Cancelled Order',
            'item_description' => 'Cancelled items',
            'amount' => 500.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->delete(route('procurements.destroy', $procurement));
        $response->assertRedirect(route('procurements.index'));
        $this->assertDatabaseMissing('procurements', ['id' => $procurement->id]);
    }
}
