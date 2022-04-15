<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'title' => 'Canadian Dollar',
            'code' => 'CAD',
            'symbol_left' => '$',
            'symbol_right' => '',
            'decimal_place' => '2',
            'value' => '0.79',
        ]);
    }
}
