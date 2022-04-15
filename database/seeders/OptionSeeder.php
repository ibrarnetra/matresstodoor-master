<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\OptionDescription;
use App\Models\Admin\Option;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $option =  Option::create([
            'type' => 'select',
        ]);

        OptionDescription::create([
            'option_id' => $option->id,
            'language_id' => '1',
            'name' => 'Select',
        ]);
    }
}
