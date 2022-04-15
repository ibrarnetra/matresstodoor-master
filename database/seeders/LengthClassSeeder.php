<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Admin\LengthClassDescription;
use App\Models\Admin\LengthClass;

class LengthClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $length_class_1 = LengthClass::create([
            'value' => '1',
        ]);

        DB::table('length_class_descriptions')->insert([
            'length_class_id' => $length_class_1->id,
            'language_id' => '1',
            'title' => 'Centimeter',
            'unit' => 'cm',
        ]);

        $length_class_2 = LengthClass::create([
            'value' => '0.39',
        ]);

        DB::table('length_class_descriptions')->insert([
            'length_class_id' => $length_class_2->id,
            'language_id' => '1',
            'title' => 'Inch',
            'unit' => 'in',
        ]);

        $length_class_3 = LengthClass::create([
            'value' => '10.00',
        ]);

        DB::table('length_class_descriptions')->insert([
            'length_class_id' => $length_class_3->id,
            'language_id' => '1',
            'title' => 'Millimeter',
            'unit' => 'mm',
        ]);
    }
}
