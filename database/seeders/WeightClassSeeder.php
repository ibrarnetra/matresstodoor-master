<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\WeightClassDescription;
use App\Models\Admin\WeightClass;

class WeightClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weight_class_1 = WeightClass::create([
            'value' => '1',
        ]);

        WeightClassDescription::create([
            'weight_class_id' => $weight_class_1->id,
            'language_id' => '1',
            'title' => 'Kilogram',
            'unit' => 'kg',
        ]);

        $weight_class_2 = WeightClass::create([
            'value' => '1000.00',
        ]);

        WeightClassDescription::create([
            'weight_class_id' => $weight_class_2->id,
            'language_id' => '1',
            'title' => 'Gram',
            'unit' => 'g',
        ]);

        $weight_class_3 = WeightClass::create([
            'value' => '2.20',
        ]);

        WeightClassDescription::create([
            'weight_class_id' => $weight_class_3->id,
            'language_id' => '1',
            'title' => 'Pound',
            'unit' => 'lb',
        ]);

        $weight_class_4 = WeightClass::create([
            'value' => '35.27',
        ]);

        WeightClassDescription::create([
            'weight_class_id' => $weight_class_4->id,
            'language_id' => '1',
            'title' => 'Ounce',
            'unit' => 'oz',
        ]);
    }
}
