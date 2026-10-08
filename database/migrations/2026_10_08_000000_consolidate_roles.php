<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure surviving target roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Procurement Officer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Project Manager', 'guard_name' => 'web']);

        // Reassign users from Administrator -> admin
        $oldAdminRole = Role::where('name', 'Administrator')->first();
        if ($oldAdminRole) {
            DB::table('model_has_roles')
                ->where('role_id', $oldAdminRole->id)
                ->update(['role_id' => $adminRole->id]);
            $oldAdminRole->delete();
        }

        // Reassign users from Viewer/Auditor or Viewer -> viewer
        $oldViewerRoles = Role::whereIn('name', ['Viewer/Auditor', 'Viewer'])->get();
        foreach ($oldViewerRoles as $oldRole) {
            DB::table('model_has_roles')
                ->where('role_id', $oldRole->id)
                ->update(['role_id' => $viewerRole->id]);
            $oldRole->delete();
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
