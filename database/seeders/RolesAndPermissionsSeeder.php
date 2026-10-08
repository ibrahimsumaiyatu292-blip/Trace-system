<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage_users',
            'create_asset',
            'edit_asset',
            'delete_asset',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create the 4 consolidated roles and assign permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions([
            'manage_users',
            'create_asset',
            'edit_asset',
            'delete_asset',
            'view_reports',
        ]);

        $roleProcurement = Role::firstOrCreate(['name' => 'Procurement Officer']);
        $roleProcurement->syncPermissions([
            'create_asset',
            'edit_asset',
            'view_reports',
        ]);

        $roleProjectManager = Role::firstOrCreate(['name' => 'Project Manager']);
        $roleProjectManager->syncPermissions([
            'view_reports',
        ]);

        $roleViewer = Role::firstOrCreate(['name' => 'viewer']);
        $roleViewer->syncPermissions([
            'view_reports',
        ]);
    }
}
