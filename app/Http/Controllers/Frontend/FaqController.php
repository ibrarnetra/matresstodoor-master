<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Faq;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $title = 'Faq';
        $meta_title = 'Faq';
        $meta_description = "Faq";
        $meta_keyword = "Faq";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.faq');

        $faqs = Faq::with([
            'eng_description'
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->orderBy('sort_order', 'ASC')
            ->get();

        return view('frontend.faq.faq', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'faqs'));
    }
}
