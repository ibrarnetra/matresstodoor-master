<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;
use App\Models\Admin\Dashboard;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $meta_title = 'Dashboard';
        $meta_description = "Dashboard";
        $meta_keyword = "Dashboard";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.dashboard');
       

        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $orders = (new Dashboard())->_getOrderData(Auth::guard('frontend')->user()->id);

        return view('frontend.dashboard.dashboard', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'countries', 'orders'));
    }
}
