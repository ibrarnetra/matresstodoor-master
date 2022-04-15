<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\PaymentMethodDescription;
use App\Models\Admin\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_method = PaymentMethod::create([
            'code' => 'COD',
        ]);

        PaymentMethodDescription::create([
            'payment_method_id' => $payment_method->id,
            'language_id' => '1',
            'name' => 'Payment on Delivery',
        ]);

        $payment_method2 = PaymentMethod::create([
            'code' => 'COC',
        ]);

        PaymentMethodDescription::create([
            'payment_method_id' => $payment_method2->id,
            'language_id' => '1',
            'name' => 'Payment on Counter',
        ]);

        $payment_method3 = PaymentMethod::create([
            'code' => 'authorize',
        ]);

        PaymentMethodDescription::create([
            'payment_method_id' => $payment_method3->id,
            'language_id' => '1',
            'name' => 'Authorize.net',
        ]);
    }
}
