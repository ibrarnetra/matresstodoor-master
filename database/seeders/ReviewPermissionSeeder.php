<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class ReviewPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Reviews']);
        Permission::create(['name' => 'Read-Reviews']);
        Permission::create(['name' => 'Edit-Reviews']);
        Permission::create(['name' => 'Add-Reviews']);
        Permission::create(['name' => 'Delete-Reviews']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Reviews', 'Read-Reviews', 'Edit-Reviews', 'Add-Reviews', 'Delete-Reviews'
            ]);
        }
    }
}
