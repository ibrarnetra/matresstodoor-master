<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class LoadingSheetPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Loading-Sheets']);
        Permission::create(['name' => 'Read-Loading-Sheets']);
        Permission::create(['name' => 'Edit-Loading-Sheets']);
        Permission::create(['name' => 'Add-Loading-Sheets']);
        Permission::create(['name' => 'Delete-Loading-Sheets']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Loading-Sheets', 'Read-Loading-Sheets', 'Edit-Loading-Sheets', 'Add-Loading-Sheets', 'Delete-Loading-Sheets'
            ]);
        }
    }
}
