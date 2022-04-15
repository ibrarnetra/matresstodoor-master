<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
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

        ### CREATE PERMISSIONS ###
        Permission::create(['name' => 'Browse-Catalog']);
        Permission::create(['name' => 'Browse-Sales']);
        Permission::create(['name' => 'Browse-Customer-Manager']);
        Permission::create(['name' => 'Browse-Attribute-Manager']);
        Permission::create(['name' => 'Browse-System']);
        Permission::create(['name' => 'Browse-Localization']);
        Permission::create(['name' => 'Browse-User-Management']);
        Permission::create(['name' => 'Browse-Taxes']);

        Permission::create(['name' => 'Browse-Stores']);
        Permission::create(['name' => 'Read-Stores']);
        Permission::create(['name' => 'Edit-Stores']);
        Permission::create(['name' => 'Add-Stores']);
        Permission::create(['name' => 'Delete-Stores']);

        Permission::create(['name' => 'Browse-Users']);
        Permission::create(['name' => 'Read-Users']);
        Permission::create(['name' => 'Edit-Users']);
        Permission::create(['name' => 'Add-Users']);
        Permission::create(['name' => 'Delete-Users']);

        Permission::create(['name' => 'Browse-Roles']);
        Permission::create(['name' => 'Read-Roles']);
        Permission::create(['name' => 'Edit-Roles']);
        Permission::create(['name' => 'Add-Roles']);
        Permission::create(['name' => 'Delete-Roles']);

        Permission::create(['name' => 'Browse-Categories']);
        Permission::create(['name' => 'Read-Categories']);
        Permission::create(['name' => 'Edit-Categories']);
        Permission::create(['name' => 'Add-Categories']);
        Permission::create(['name' => 'Delete-Categories']);

        Permission::create(['name' => 'Browse-Manufacturers']);
        Permission::create(['name' => 'Read-Manufacturers']);
        Permission::create(['name' => 'Edit-Manufacturers']);
        Permission::create(['name' => 'Add-Manufacturers']);
        Permission::create(['name' => 'Delete-Manufacturers']);

        Permission::create(['name' => 'Browse-Attributes']);
        Permission::create(['name' => 'Read-Attributes']);
        Permission::create(['name' => 'Edit-Attributes']);
        Permission::create(['name' => 'Add-Attributes']);
        Permission::create(['name' => 'Delete-Attributes']);

        Permission::create(['name' => 'Browse-Attribute-Groups']);
        Permission::create(['name' => 'Read-Attribute-Groups']);
        Permission::create(['name' => 'Edit-Attribute-Groups']);
        Permission::create(['name' => 'Add-Attribute-Groups']);
        Permission::create(['name' => 'Delete-Attribute-Groups']);

        Permission::create(['name' => 'Browse-Options']);
        Permission::create(['name' => 'Read-Options']);
        Permission::create(['name' => 'Edit-Options']);
        Permission::create(['name' => 'Add-Options']);
        Permission::create(['name' => 'Delete-Options']);

        Permission::create(['name' => 'Browse-Languages']);
        Permission::create(['name' => 'Read-Languages']);
        Permission::create(['name' => 'Edit-Languages']);
        Permission::create(['name' => 'Add-Languages']);
        Permission::create(['name' => 'Delete-Languages']);

        Permission::create(['name' => 'Browse-Currencies']);
        Permission::create(['name' => 'Read-Currencies']);
        Permission::create(['name' => 'Edit-Currencies']);
        Permission::create(['name' => 'Add-Currencies']);
        Permission::create(['name' => 'Delete-Currencies']);

        Permission::create(['name' => 'Browse-Stock-Statuses']);
        Permission::create(['name' => 'Read-Stock-Statuses']);
        Permission::create(['name' => 'Edit-Stock-Statuses']);
        Permission::create(['name' => 'Add-Stock-Statuses']);
        Permission::create(['name' => 'Delete-Stock-Statuses']);

        Permission::create(['name' => 'Browse-Order-Statuses']);
        Permission::create(['name' => 'Read-Order-Statuses']);
        Permission::create(['name' => 'Edit-Order-Statuses']);
        Permission::create(['name' => 'Add-Order-Statuses']);
        Permission::create(['name' => 'Delete-Order-Statuses']);

        Permission::create(['name' => 'Browse-Countries']);
        Permission::create(['name' => 'Read-Countries']);
        Permission::create(['name' => 'Edit-Countries']);
        Permission::create(['name' => 'Add-Countries']);
        Permission::create(['name' => 'Delete-Countries']);

        Permission::create(['name' => 'Browse-Zones']);
        Permission::create(['name' => 'Read-Zones']);
        Permission::create(['name' => 'Edit-Zones']);
        Permission::create(['name' => 'Add-Zones']);
        Permission::create(['name' => 'Delete-Zones']);

        Permission::create(['name' => 'Browse-Tax-Classes']);
        Permission::create(['name' => 'Read-Tax-Classes']);
        Permission::create(['name' => 'Edit-Tax-Classes']);
        Permission::create(['name' => 'Add-Tax-Classes']);
        Permission::create(['name' => 'Delete-Tax-Classes']);

        Permission::create(['name' => 'Browse-Tax-Rates']);
        Permission::create(['name' => 'Read-Tax-Rates']);
        Permission::create(['name' => 'Edit-Tax-Rates']);
        Permission::create(['name' => 'Add-Tax-Rates']);
        Permission::create(['name' => 'Delete-Tax-Rates']);

        Permission::create(['name' => 'Browse-Length-Classes']);
        Permission::create(['name' => 'Read-Length-Classes']);
        Permission::create(['name' => 'Edit-Length-Classes']);
        Permission::create(['name' => 'Add-Length-Classes']);
        Permission::create(['name' => 'Delete-Length-Classes']);

        Permission::create(['name' => 'Browse-Weight-Classes']);
        Permission::create(['name' => 'Read-Weight-Classes']);
        Permission::create(['name' => 'Edit-Weight-Classes']);
        Permission::create(['name' => 'Add-Weight-Classes']);
        Permission::create(['name' => 'Delete-Weight-Classes']);

        Permission::create(['name' => 'Browse-Geozones']);
        Permission::create(['name' => 'Read-Geozones']);
        Permission::create(['name' => 'Edit-Geozones']);
        Permission::create(['name' => 'Add-Geozones']);
        Permission::create(['name' => 'Delete-Geozones']);

        Permission::create(['name' => 'Browse-Products']);
        Permission::create(['name' => 'Read-Products']);
        Permission::create(['name' => 'Edit-Products']);
        Permission::create(['name' => 'Add-Products']);
        Permission::create(['name' => 'Delete-Products']);

        Permission::create(['name' => 'Browse-Customer-Groups']);
        Permission::create(['name' => 'Read-Customer-Groups']);
        Permission::create(['name' => 'Edit-Customer-Groups']);
        Permission::create(['name' => 'Add-Customer-Groups']);
        Permission::create(['name' => 'Delete-Customer-Groups']);

        Permission::create(['name' => 'Browse-Customers']);
        Permission::create(['name' => 'Read-Customers']);
        Permission::create(['name' => 'Edit-Customers']);
        Permission::create(['name' => 'Add-Customers']);
        Permission::create(['name' => 'Delete-Customers']);

        Permission::create(['name' => 'Browse-Orders']);
        Permission::create(['name' => 'Read-Orders']);
        Permission::create(['name' => 'Edit-Orders']);
        Permission::create(['name' => 'Add-Orders']);
        Permission::create(['name' => 'Delete-Orders']);

        Permission::create(['name' => 'Browse-Settings']);
        Permission::create(['name' => 'Read-Settings']);
        Permission::create(['name' => 'Edit-Settings']);
        Permission::create(['name' => 'Add-Settings']);
        Permission::create(['name' => 'Delete-Settings']);

        Permission::create(['name' => 'Browse-Store-Locations']);
        Permission::create(['name' => 'Read-Store-Locations']);
        Permission::create(['name' => 'Edit-Store-Locations']);
        Permission::create(['name' => 'Add-Store-Locations']);
        Permission::create(['name' => 'Delete-Store-Locations']);

        Permission::create(['name' => 'Browse-Pages']);
        Permission::create(['name' => 'Read-Pages']);
        Permission::create(['name' => 'Edit-Pages']);
        Permission::create(['name' => 'Add-Pages']);
        Permission::create(['name' => 'Delete-Pages']);

        ### CREATE ROLE ###
        $role = Role::create(['name' => 'Super Admin']);
        ### GIVE ALL PERMISSIONS TO 'Super Admin' ###
        $role->givePermissionTo(Permission::all());
        ### USER WITH ROLE 'Super Admin' ###
        $super_admin = User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'username' => 'super_admin',
            'email' => 'superadmin@mattresstodoor.com',
            'password' => Hash::make('123456789')
        ]);
        $super_admin->assignRole('Super Admin');

        ### CREATE ROLE ###
        $role = Role::create(['name' => 'Sales Rep']);
        ### GIVE ALL PERMISSIONS TO 'Sales Rep' ###
        $role->givePermissionTo([
            'Browse-Catalog', 'Browse-Sales', 'Browse-Attribute-Manager',
            'Browse-Categories', 'Read-Categories', 'Edit-Categories', 'Add-Categories', 'Delete-Categories',
            'Browse-Manufacturers', 'Read-Manufacturers', 'Edit-Manufacturers', 'Add-Manufacturers', 'Delete-Manufacturers',
            'Browse-Attributes', 'Read-Attributes', 'Edit-Attributes', 'Add-Attributes', 'Delete-Attributes',
            'Browse-Attribute-Groups', 'Read-Attribute-Groups', 'Edit-Attribute-Groups', 'Add-Attribute-Groups', 'Delete-Attribute-Groups',
            'Browse-Options', 'Read-Options', 'Edit-Options', 'Add-Options', 'Delete-Options',
            'Browse-Products', 'Read-Products', 'Edit-Products', 'Add-Products', 'Delete-Products',
            'Browse-Orders', 'Read-Orders', 'Edit-Orders', 'Add-Orders', 'Delete-Orders'
        ]);
        ### USER WITH ROLE 'Sales Rep' ###
        $sales_rep = User::create([
            'first_name' => 'sales',
            'last_name' => 'rep',
            'username' => 'sales_rep',
            'email' => 'salesrep@mattresstodoor.com',
            'password' => Hash::make('123456789')
        ]);
        $sales_rep->assignRole('Sales Rep');

        ### CREATE ROLE ###
        $role = Role::create(['name' => 'Dispatch Manager']);
        ### GIVE ALL PERMISSIONS TO 'Dispatch Manager' ###
        $role->givePermissionTo([
            'Browse-Sales',
            'Browse-Orders', 'Read-Orders', 'Edit-Orders', 'Add-Orders', 'Delete-Orders'
        ]);
        ### USER WITH ROLE 'Dispatch Manager' ###
        $dispatch_manager = User::create([
            'first_name' => 'dispatch',
            'last_name' => 'manager',
            'username' => 'dispatch_manager',
            'email' => 'dispatch@mattresstodoor.com',
            'password' => Hash::make('123456789')
        ]);
        $dispatch_manager->assignRole('Dispatch Manager');
    }
}
