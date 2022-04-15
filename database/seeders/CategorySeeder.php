<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\CategoryDescription;
use App\Models\Admin\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::create([
            'slug' => 'default-1',
            'sort_order' => '1',
        ]);

        CategoryDescription::create([
            'category_id' => $category->id,
            'language_id' => '1',
            'name' => 'Default',
            'description' => 'Default',
            'meta_title' => 'Default',
            'meta_description' => 'Default',
            'meta_keyword' => 'Default',
        ]);
    }
}
