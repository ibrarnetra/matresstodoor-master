<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\PaymentMethodDescription;
use App\Models\Admin\PaymentMethod;

class PaymentLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_method = PaymentMethod::create([
            'code' => 'p-link',
        ]);

        PaymentMethodDescription::create([
            'payment_method_id' => $payment_method->id,
            'language_id' => '1',
            'name' => 'Payment Link',
        ]);
    }
}
