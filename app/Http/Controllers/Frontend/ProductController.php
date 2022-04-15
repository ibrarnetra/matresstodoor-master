<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productDetail($slug)
    {
        $product = (new Product())->_getProductWithSlug($slug);

        $title = $product->eng_description->name;
        $meta_title = $product->eng_description->meta_title;
        $meta_description = $product->eng_description->meta_description;
        $meta_keyword = $product->eng_description->meta_keyword;
        $meta_image = ($product->thumbnail_image) ? asset('storage/product_images/' . $product->thumbnail_image->image) : asset('frontend_assets/images/600_600.png');
        $meta_url = route('frontend.productDetail', ['slug' => $slug]);

        return view('frontend.product.detail', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'product'));
    }

    public function quickView($slug)
    {
        $product = (new Product())->_getProductWithSlug($slug);
        $view = view('frontend.product.quick_view', compact('product'))->render();

        return ['status' => true, 'data' => $view];
    }
}
