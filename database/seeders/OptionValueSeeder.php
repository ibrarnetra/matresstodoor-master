<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\OptionValueDescription;
use App\Models\Admin\OptionValue;
use App\Models\Admin\Option;

class OptionValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $option = Option::first();
        $option_value1 = OptionValue::create([
            'option_id' => $option->id,
        ]);

        $option_value2 = OptionValue::create([
            'option_id' => $option->id,
        ]);

        OptionValueDescription::create([
            'option_value_id' => $option_value1->id,
            'language_id' => '1',
            'option_id' => '1',
            'name' => 'option1',
        ]);

        OptionValueDescription::create([
            'option_value_id' => $option_value2->id,
            'language_id' => '1',
            'option_id' => '1',
            'name' => 'option2',
        ]);
    }
}
