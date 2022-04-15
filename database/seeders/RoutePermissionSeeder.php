<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoutePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Browse-Routes']);
        Permission::create(['name' => 'Read-Routes']);
        Permission::create(['name' => 'Edit-Routes']);
        Permission::create(['name' => 'Add-Routes']);
        Permission::create(['name' => 'Delete-Routes']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Routes', 'Read-Routes', 'Edit-Routes', 'Add-Routes', 'Delete-Routes'
            ]);
        }
    }
}
