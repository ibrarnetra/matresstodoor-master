<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\AttributeGroup;
use App\Models\Admin\AttributeDescription;
use App\Models\Admin\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attribute_group = AttributeGroup::first();
        $attribute1 =  Attribute::create([
            'attribute_group_id' => $attribute_group->id,
            'sort_order' => '1',
        ]);

        $attribute2 =  Attribute::create([
            'attribute_group_id' => $attribute_group->id,
            'sort_order' => '1',
        ]);

        $attribute3 =  Attribute::create([
            'attribute_group_id' => $attribute_group->id,
            'sort_order' => '1',
        ]);

        AttributeDescription::create([
            'attribute_id' => $attribute1->id,
            'language_id' => '1',
            'name' => 'Attribute 1',
        ]);

        AttributeDescription::create([
            'attribute_id' => $attribute2->id,
            'language_id' => '1',
            'name' => 'Attribute 2',
        ]);

        AttributeDescription::create([
            'attribute_id' => $attribute3->id,
            'language_id' => '1',
            'name' => 'Attribute 3',
        ]);
    }
}
