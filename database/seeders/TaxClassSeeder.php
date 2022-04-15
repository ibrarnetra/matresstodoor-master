<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\TaxRule;
use App\Models\Admin\TaxClass;

class TaxClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax_class_1 = TaxClass::create([
            'title' => 'Ontario Taxes',
            'description' => 'Ontario Taxes',
        ]);

        TaxRule::create([
            'tax_class_id' => $tax_class_1->id,
            'tax_rate_id' => '1',
            'based' => 'shipping',
            'priority' => '1'
        ]);

        $tax_class_2 = TaxClass::create([
            'title' => 'Alberta Taxes',
            'description' => 'Alberta Taxes',
        ]);

        TaxRule::create([
            'tax_class_id' => $tax_class_2->id,
            'tax_rate_id' => '2',
            'based' => 'shipping',
            'priority' => '1'
        ]);

        $tax_class_3 = TaxClass::create([
            'title' => 'Manitoba Tax rate',
            'description' => 'Manitoba Tax rate',
        ]);

        TaxRule::create([
            'tax_class_id' => $tax_class_3->id,
            'tax_rate_id' => '3',
            'based' => 'shipping',
            'priority' => '1'
        ]);
    }
}
