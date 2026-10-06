<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Procurement;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_asset_creation_writes_access_log(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Administrator');

        $response = $this->actingAs($user)->post(route('assets.store'), [
            'name' => 'Audit Test Server',
            'category' => 'Infrastructure',
            'value' => 3000.00,
            'assigned_project' => 'TRACE Core',
            'status' => 'Available',
        ]);

        $response->assertRedirect(route('assets.index'));

        $asset = Asset::where('name', 'Audit Test Server')->first();

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'created',
            'target_table' => 'assets',
            'target_id' => $asset->id,
        ]);
    }

    public function test_asset_update_and_deletion_writes_access_logs(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Administrator');

        $asset = Asset::create([
            'name' => 'Audit Switch',
            'category' => 'Networking',
            'value' => 500.00,
            'assigned_project' => 'Lab 1',
            'status' => 'Available',
        ]);

        // Update
        $this->actingAs($user)->put(route('assets.update', $asset), [
            'name' => 'Audit Switch Managed',
            'category' => 'Networking',
            'value' => 550.00,
            'assigned_project' => 'Lab 1',
            'status' => 'In Use',
        ]);

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'updated',
            'target_table' => 'assets',
            'target_id' => $asset->id,
        ]);

        // Delete
        $this->actingAs($user)->delete(route('assets.destroy', $asset));

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'deleted',
            'target_table' => 'assets',
            'target_id' => $asset->id,
        ]);
    }

    public function test_procurement_actions_write_access_logs(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Administrator');

        // Create procurement
        $this->actingAs($user)->post(route('procurements.store'), [
            'project_name' => 'Campus Wi-Fi 6',
            'item_description' => '20 Access Points',
            'amount' => 4000.00,
            'status' => 'pending',
        ]);

        $procurement = Procurement::where('project_name', 'Campus Wi-Fi 6')->first();

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'created',
            'target_table' => 'procurements',
            'target_id' => $procurement->id,
        ]);

        // Update procurement
        $this->actingAs($user)->put(route('procurements.update', $procurement), [
            'project_name' => 'Campus Wi-Fi 6',
            'item_description' => '20 Access Points with PoE Switches',
            'amount' => 5500.00,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'updated',
            'target_table' => 'procurements',
            'target_id' => $procurement->id,
        ]);

        // Delete procurement
        $this->actingAs($user)->delete(route('procurements.destroy', $procurement));

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $user->id,
            'action' => 'deleted',
            'target_table' => 'procurements',
            'target_id' => $procurement->id,
        ]);
    }
}
