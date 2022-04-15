<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\AttributeGroupDescription;
use App\Models\Admin\AttributeGroup;

class AttributeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attribute_group =  AttributeGroup::create([
            'sort_order' => '1',
            'is_editable' => getConstant('IS_NOT_EDITABLE'),
        ]);

        AttributeGroupDescription::create([
            'attribute_group_id' => $attribute_group->id,
            'language_id' => '1',
            'name' => 'Default',
        ]);
    }
}
