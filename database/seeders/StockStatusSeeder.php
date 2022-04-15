<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\StockStatus;

class StockStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StockStatus::create([
            'language_id' => '1',
            'name' => 'In Stock',
        ]);

        StockStatus::create([
            'language_id' => '1',
            'name' => 'Pre-Order',
        ]);

        StockStatus::create([
            'language_id' => '1',
            'name' => 'Out Of Stock',
        ]);

        StockStatus::create([
            'language_id' => '1',
            'name' => '2-3 Days',
        ]);
    }
}
