<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SliderPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Sliders']);
        Permission::create(['name' => 'Read-Sliders']);
        Permission::create(['name' => 'Edit-Sliders']);
        Permission::create(['name' => 'Add-Sliders']);
        Permission::create(['name' => 'Delete-Sliders']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Sliders', 'Read-Sliders', 'Edit-Sliders', 'Add-Sliders', 'Delete-Sliders'
            ]);
        }
    }
}
