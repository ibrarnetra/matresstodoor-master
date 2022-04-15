<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class FaqPermissionSeeder extends Seeder
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

        Permission::create(['name' => 'Browse-Faqs']);
        Permission::create(['name' => 'Read-Faqs']);
        Permission::create(['name' => 'Edit-Faqs']);
        Permission::create(['name' => 'Add-Faqs']);
        Permission::create(['name' => 'Delete-Faqs']);

        $roles = Role::whereIn('name', ['Super Admin', 'Dispatch Manager'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo([
                'Browse-Faqs', 'Read-Faqs', 'Edit-Faqs', 'Add-Faqs', 'Delete-Faqs'
            ]);
        }
    }
}
