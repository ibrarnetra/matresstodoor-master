<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SubscriberPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Subscribers']);
        Permission::create(['name' => 'Read-Subscribers']);
        Permission::create(['name' => 'Edit-Subscribers']);
        Permission::create(['name' => 'Add-Subscribers']);
        Permission::create(['name' => 'Delete-Subscribers']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Subscribers', 'Read-Subscribers', 'Edit-Subscribers', 'Add-Subscribers', 'Delete-Subscribers'
            ]);
        }
    }
}
