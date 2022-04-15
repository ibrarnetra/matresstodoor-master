<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Manufacturer;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufacturer = Manufacturer::create([
            'name' => 'Default',
        ]);

        ### SYNCING FOR PIVOT ###
        $manufacturer->stores()->sync(['1']);
    }
}
