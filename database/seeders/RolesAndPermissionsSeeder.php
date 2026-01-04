<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view supervisor dashboard']);
        Permission::create(['name' => 'use agent dashboard']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'Asesor SAC']);
        $role->givePermissionTo('use agent dashboard');

        $role = Role::create(['name' => 'Torre de Control']);
        $role->givePermissionTo(['use agent dashboard', 'view supervisor dashboard']);

        $role = Role::create(['name' => 'Administrator']);
        $role->givePermissionTo(Permission::all()); // El admin puede todo
    }
}
