<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Processing"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Shipped"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Canceled"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Complete"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Denied"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Canceled Reversal"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Failed"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Refunded"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Reversed"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Chargeback"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Pending"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Voided"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Processed"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Expired"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Done"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Postpone"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Ready"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "VM"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Partially Done"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Already Done"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Hold"
        ]);
        OrderStatus::create([
            "language_id" => 1,
            "name" => "Moved"
        ]);
    }
}
