<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Review;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Reviews')) {
        ### CONST ###
        $menu_1 = 'customer-manager';
        $active = 'reviews';
        $title = 'Reviews';

        return view('admin.reviews.index', compact('menu_1', 'active', 'title'));
        }
        else{
            return redirect()->route('dashboard.index');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Review())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Review())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Review())->_bulkDelete($request);
    }
}
