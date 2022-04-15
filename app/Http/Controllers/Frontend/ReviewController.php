<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Review;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            "product_id" => "required",
            "name" => auth()->guard('frontend')->check() ? "" : "required",
            "email" => auth()->guard('frontend')->check() ? "" : "required|email",
            "review" => "required",
            "rating" => "required",
        ]);

        $insert = (new Review())->_store($request);

        if ($insert) {
            return back()->with('success', 'Successfully submitted a review.');
        }
    }
}
