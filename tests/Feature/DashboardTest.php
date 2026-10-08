<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Procurement;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_dashboard_displays_summary_metrics_and_grouped_statuses(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        Asset::create([
            'name' => 'Dashboard Asset 1',
            'category' => 'Hardware',
            'value' => 1000.00,
            'status' => 'Available',
        ]);

        Procurement::create([
            'project_name' => 'Project Pending',
            'item_description' => 'Item 1',
            'amount' => 500.00,
            'status' => 'pending',
        ]);

        Procurement::create([
            'project_name' => 'Project Approved',
            'item_description' => 'Item 2',
            'amount' => 1500.00,
            'status' => 'approved',
        ]);

        Procurement::create([
            'project_name' => 'Project Completed',
            'item_description' => 'Item 3',
            'amount' => 2500.00,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Managed Assets');
        $response->assertSee('Procurement Requests');
        $response->assertSee('Pending');
        $response->assertSee('Approved');
        $response->assertSee('Completed');

        // Audit log link should NOT be present for viewer user
        $response->assertDontSee(route('audit-logs.index'));
    }

    public function test_dashboard_displays_audit_log_link_for_admins(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('audit-logs.index'));
    }

    public function test_admin_can_access_audit_logs_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('System Audit Trail');
    }

    public function test_non_admin_cannot_access_audit_logs_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $response = $this->actingAs($user)->get(route('audit-logs.index'));
        $response->assertStatus(403);
    }
}
