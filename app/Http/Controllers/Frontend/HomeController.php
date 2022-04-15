<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Home;
use App\Models\Admin\Slider;
use App\Models\Admin\Page;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $cmsData = (new Page('en'))->getCmsPage('home');

        $title = ($cmsData) ? $cmsData->title : 'Home';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Home';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Home";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Home";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.home');

        $categories = (new Home())->_getLimitedCategories();
        $categories_with_products = (new Home())->_getLimitedCategoriesWithProducts();

        $info_modals = [];
        $modals = [
            'info-modal-1',
            'info-modal-2',
            'info-modal-3'
        ];
        foreach ($modals as $modal) {
            $data = (new Page())->getCmsPage($modal);
            $content = "";
            if ($data) {
                $content = $data->content;
            }
            $info_modals[$modal] = $content;
        }

        return view('frontend.home.home', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'categories', 'categories_with_products', 'info_modals'));
    }
}
