<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Admin\TaxRate;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxRate::create([
            'geo_zone_id' => '1',
            'name' => 'Ontarion Tax Rate',
            'rate' => '12.49',
            'type' => 'fixed',
        ]);

        DB::table('customer_group_tax_rate')->insert([
            'customer_group_id' => '1',
            'tax_rate_id' => '1',
        ]);

        TaxRate::create([
            'geo_zone_id' => '2',
            'name' => 'Alberta Tax Rate',
            'rate' => '13.75',
            'type' => 'percentage',
        ]);

        DB::table('customer_group_tax_rate')->insert([
            'customer_group_id' => '1',
            'tax_rate_id' => '2',
        ]);

        TaxRate::create([
            'geo_zone_id' => '3',
            'name' => 'Manitoba Tax rate',
            'rate' => '18.75',
            'type' => 'percentage',
        ]);

        DB::table('customer_group_tax_rate')->insert([
            'customer_group_id' => '1',
            'tax_rate_id' => '3',
        ]);
    }
}
