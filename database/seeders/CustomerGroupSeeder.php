<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\CustomerGroupDescription;
use App\Models\Admin\CustomerGroup;

class CustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_group = CustomerGroup::create([
            'approval' => '1',
            'is_editable' => getConstant('IS_NOT_EDITABLE'),
        ]);

        CustomerGroupDescription::create([
            'customer_group_id' => $customer_group->id,
            'language_id' => '1',
            'name' => 'Default',
            'description' => 'Default',
        ]);
    }
}
