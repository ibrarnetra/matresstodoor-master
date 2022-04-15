<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Page;
use App\Models\Admin\PageDescription;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page1 = Page::create([
            'slug' => 'about-us',
        ]);

        PageDescription::create([
            'page_id' => $page1->id,
            'title' => 'About Us',
            'content' => 'About Us',
            'meta_title' => 'About Us',
            'meta_keyword' => 'About Us',
            'meta_description' => 'About Us',
        ]);

        $page2 = Page::create([
            'slug' => 'terms-and-conditions',
        ]);

        PageDescription::create([
            'page_id' => $page2->id,
            'title' => 'Terms and Conditions',
            'content' => 'Terms and Conditions',
            'meta_title' => 'Terms and Conditions',
            'meta_keyword' => 'Terms and Conditions',
            'meta_description' => 'Terms and Conditions',
        ]);

        $page3 = Page::create([
            'slug' => 'privacy-policy',
        ]);

        PageDescription::create([
            'page_id' => $page3->id,
            'title' => 'Privacy Policy',
            'content' => 'Privacy Policy',
            'meta_title' => 'Privacy Policy',
            'meta_keyword' => 'Privacy Policy',
            'meta_description' => 'Privacy Policy',
        ]);
    }
}
