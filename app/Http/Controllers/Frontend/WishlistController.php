<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function wishlist()
    {
        $title = 'Wishlist';
        $meta_title = 'Wishlist';
        $meta_description = "Wishlist";
        $meta_keyword = "Wishlist";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.wishlist');

        $wishlist = (new Product())->_getWishlistProducts(Auth::guard('frontend')->user()->id);

        return view('frontend.wishlist.wishlist', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'wishlist'));
    }

    public function addToWishlist(Request $request, $product_id)
    {
        // return $request;
        $response = ['status' => true, 'data' => 'Successfully added item to wishlist.'];
        $user_id = Auth::guard('frontend')->user()->id;
        try {
            $product = Product::where('id', $product_id)->first();
            $product_id = $product->id;
        } catch (Exception $th) {
            $product = Product::where('slug', $product_id)->first();
            $product_id = $product->id;
        }
        $res = (new Product())->_addToWishlist($product_id, $user_id);
        if (!$res) {
            $response['status'] = false;
            $response['data'] = '';
        }
        return json_encode($response);
    }

    public function removeFromWishlist(Request $request, $product_id)
    {
        // return $request;
        $response = ['status' => true, 'data' => 'Successfully removed item from wishlist.'];
        $user_id = Auth::guard('frontend')->user()->id;
        $res = (new Product())->_removeFromWishlist($product_id, $user_id);
        if (!$res) {
            $response['status'] = false;
            $response['data'] = '';
        }
        return json_encode($response);
    }
}
