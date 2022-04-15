<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Store;
use App\Models\Admin\Setting;
use App\Models\Admin\Location;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            'name' => 'Default',
            'manager' => 'Default',
            'address' => 'Default',
            'email' => 'default@default.com',
            'telephone' => '123456789',
        ]);
    }
}
