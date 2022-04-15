<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Page;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function shop(Request $request)
    {
        $cmsData = (new Page('en'))->getCmsPage('shop');

        $title = ($cmsData) ? $cmsData->title : 'Shop';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Shop';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Shop";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Shop";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.shop');

        $max_price = (new Product())->_getMaxPrice();
        $min_price = (new Product())->_getMinPrice();
        $products = (new Product())->_getAllProducts($request);

        return view('frontend.shop.shop', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'products', 'max_price', 'min_price'));
    }
}
