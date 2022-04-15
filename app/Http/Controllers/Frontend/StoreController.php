<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Store;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Stores';
        $meta_title = 'Stores';
        $meta_description = "Stores";
        $meta_keyword = "Stores";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.stores');

        $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get(['id', 'name', 'telephone', 'email', 'address', 'lat', 'lng'])->toArray();

        return view('frontend.store.store', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'stores'));
    }
}
