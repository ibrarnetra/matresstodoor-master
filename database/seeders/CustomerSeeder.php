<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Admin\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'customer_group_id' => '1',
            'first_name' => 'Demo-1',
            'last_name' => 'Demo-1',
            'email' => 'demo1@demo.com',
            'telephone' => '123-456-7890',
            'password' => Hash::make('123456789'),
        ]);

        Customer::create([
            'customer_group_id' => '1',
            'first_name' => 'Demo-2',
            'last_name' => 'Demo-2',
            'email' => 'demo2@demo.com',
            'telephone' => '123-456-7890',
            'password' => Hash::make('123456789'),
            'status' => '0',
        ]);
    }
}
