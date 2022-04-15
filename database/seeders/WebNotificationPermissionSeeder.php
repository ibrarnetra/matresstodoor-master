<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class WebNotificationPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Web-Notifications']);
        Permission::create(['name' => 'Read-Web-Notifications']);
        Permission::create(['name' => 'Edit-Web-Notifications']);
        Permission::create(['name' => 'Add-Web-Notifications']);
        Permission::create(['name' => 'Delete-Web-Notifications']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Web-Notifications', 'Read-Web-Notifications', 'Edit-Web-Notifications', 'Add-Web-Notifications', 'Delete-Web-Notifications'
            ]);
        }
    }
}
