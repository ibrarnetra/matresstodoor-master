<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\ShippingMethodDescription;
use App\Models\Admin\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standard = ShippingMethod::create([
            'cost' => '0',
            'code' => 'MTD',
            'deliver_in_days' => '7',
        ]);

        ShippingMethodDescription::create([
            'shipping_method_id' => $standard->id,
            'language_id' => '1',
            'name' => 'MTD Shipping',
        ]);
    }
}
