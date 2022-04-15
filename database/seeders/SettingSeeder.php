<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = [
            'config_meta_title' => 'Default Meta Title',
            'config_country_id' => '38',
            'config_zone_id' => '602',
            'config_currency' => 'CAD',
            'config_length_class_id' => '1',
            'config_weight_class_id' => '1',
            'config_invoice_prefix' => 'INV-2021-00',
        ];

        foreach ($config as $key => $value) {
            Setting::create([
                'code' => 'config',
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
