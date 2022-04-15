<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Customer;
use App\Models\Admin\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_1 = Customer::where('first_name', 'Demo-1')->first();
        Address::create([
            "customer_id" => $customer_1->id,
            "first_name" => $customer_1->first_name,
            "last_name" => $customer_1->last_name,
            "company" => "",
            "address_1" => "Address-1 Alberta, Canada",
            "address_2" => "",
            "city" => "Alberta",
            "postcode" => "",
            "country_id" => "38",
            "zone_id" => "602",
        ]);
        Address::create([
            "customer_id" => $customer_1->id,
            "first_name" => $customer_1->first_name,
            "last_name" => $customer_1->last_name,
            "company" => "",
            "address_1" => "Address-2 Ontario, Canada",
            "address_2" => "",
            "city" => "Ontario",
            "postcode" => "",
            "country_id" => "38",
            "zone_id" => "610",
        ]);

        $customer_2 = Customer::where('first_name', 'Demo-2')->first();
        Address::create([
            "customer_id" => $customer_2->id,
            "first_name" => $customer_2->first_name,
            "last_name" => $customer_2->last_name,
            "company" => "",
            "address_1" => "Address-1 Punjab, Pakistan",
            "address_2" => "",
            "city" => "Lahore",
            "postcode" => "",
            "country_id" => "162",
            "zone_id" => "2366",
        ]);
        Address::create([
            "customer_id" => $customer_2->id,
            "first_name" => $customer_2->first_name,
            "last_name" => $customer_2->last_name,
            "company" => "",
            "address_1" => "Address-2 Sindh, Pakistan",
            "address_2" => "",
            "city" => "Karachi",
            "postcode" => "",
            "country_id" => "162",
            "zone_id" => "2367",
        ]);
    }
}
