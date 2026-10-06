<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Procurement;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetOwnershipPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_project_manager_can_view_assets_and_procurements(): void
    {
        $pmUser = User::factory()->create();
        $pmUser->assignRole('Project Manager');

        $asset = Asset::create([
            'name' => 'PM Assigned Projector',
            'category' => 'Electronics',
            'value' => 1200.00,
            'assigned_project' => 'Project Alpha',
            'status' => 'Available',
            'created_by' => $pmUser->id,
        ]);

        $procurement = Procurement::create([
            'project_name' => 'Project Alpha',
            'item_description' => 'HDMI Cables',
            'amount' => 150.00,
            'status' => 'pending',
        ]);

        // View index & show pages returns 200 OK
        $this->actingAs($pmUser)->get(route('assets.index'))->assertStatus(200)->assertSee('PM Assigned Projector');
        $this->actingAs($pmUser)->get(route('assets.show', $asset))->assertStatus(200);
        $this->actingAs($pmUser)->get(route('procurements.index'))->assertStatus(200)->assertSee('Project Alpha');
        $this->actingAs($pmUser)->get(route('procurements.show', $procurement))->assertStatus(200);
    }

    public function test_project_manager_cannot_create_edit_or_delete_assets_or_procurements(): void
    {
        $pmUser = User::factory()->create();
        $pmUser->assignRole('Project Manager');

        $ownAsset = Asset::create([
            'name' => 'PM Laptop',
            'category' => 'Computers',
            'value' => 1500.00,
            'assigned_project' => 'Project Alpha',
            'status' => 'Available',
            'created_by' => $pmUser->id,
        ]);

        $procurement = Procurement::create([
            'project_name' => 'Project Alpha',
            'item_description' => 'Stationery',
            'amount' => 200.00,
            'status' => 'pending',
        ]);

        // Blocked from creating assets
        $this->actingAs($pmUser)->get(route('assets.create'))->assertStatus(403);
        $this->actingAs($pmUser)->post(route('assets.store'), [
            'name' => 'New Asset',
            'category' => 'General',
            'value' => 100.00,
            'status' => 'Available',
        ])->assertStatus(403);

        // Blocked from editing assets (even created by them)
        $this->actingAs($pmUser)->get(route('assets.edit', $ownAsset))->assertStatus(403);
        $this->actingAs($pmUser)->put(route('assets.update', $ownAsset), [
            'name' => 'Attempted Edit',
            'category' => 'Computers',
            'value' => 1500.00,
            'status' => 'Available',
        ])->assertStatus(403);

        // Blocked from deleting assets
        $this->actingAs($pmUser)->delete(route('assets.destroy', $ownAsset))->assertStatus(403);

        // Blocked from creating/editing/deleting procurements
        $this->actingAs($pmUser)->get(route('procurements.create'))->assertStatus(403);
        $this->actingAs($pmUser)->get(route('procurements.edit', $procurement))->assertStatus(403);
        $this->actingAs($pmUser)->delete(route('procurements.destroy', $procurement))->assertStatus(403);
    }

    public function test_project_manager_view_hides_action_buttons(): void
    {
        $pmUser = User::factory()->create();
        $pmUser->assignRole('Project Manager');

        $asset = Asset::create([
            'name' => 'Shared Printer',
            'category' => 'Office Equipment',
            'value' => 400.00,
            'assigned_project' => 'Project Beta',
            'status' => 'Available',
            'created_by' => $pmUser->id,
        ]);

        $response = $this->actingAs($pmUser)->get(route('assets.index'));
        $response->assertStatus(200);

        // Should not see Add New Asset button or Edit/Delete links
        $response->assertDontSee('+ Add New Asset');
        $response->assertDontSee(route('assets.edit', $asset));
    }

    public function test_admin_and_procurement_officer_can_edit_any_asset(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $procurementOfficer = User::factory()->create();
        $procurementOfficer->assignRole('Procurement Officer');

        $creator = User::factory()->create();

        $asset = Asset::create([
            'name' => 'Universal Asset',
            'category' => 'General',
            'value' => 300.00,
            'assigned_project' => 'General',
            'status' => 'Available',
            'created_by' => $creator->id,
        ]);

        // Procurement Officer can edit asset
        $this->actingAs($procurementOfficer)->get(route('assets.edit', $asset))->assertStatus(200);

        // Admin can edit asset
        $this->actingAs($admin)->get(route('assets.edit', $asset))->assertStatus(200);
    }
}
