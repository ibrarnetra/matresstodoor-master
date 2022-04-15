<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ### CREATE 'English' LANGUAGE ###
        Language::create([
            'name' => 'English',
            'code' => 'en'
        ]);

        ### CREATE 'French' LANGUAGE ###
        Language::create([
            'name' => 'French',
            'code' => 'fr',
            'status' => '0'
        ]);
    }
}
