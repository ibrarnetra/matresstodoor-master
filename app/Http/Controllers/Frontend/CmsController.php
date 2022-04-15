<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Page;
use App\Http\Controllers\Controller;

class CmsController extends Controller
{
    public function loadCmsPage(Request $request)
    {
        $current_uri = request()->segments()[0];
        $code = (isset($request->lang) && !is_null($request->lang)) ? $request->lang : "en";
        $cmsData = (new Page($code))->getCmsPage($current_uri);
        if ($cmsData) {
            $title = $cmsData->title;
            $meta_title = $cmsData->meta_title;
            $meta_description = $cmsData->meta_description;
            $meta_keyword = $cmsData->meta_keyword;
            $meta_image = asset('storage/config_logos/' . getWebsiteLogo());

            if ($current_uri == "about-us") {
                $meta_url = route('frontend.aboutUs');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "terms-and-conditions") {
                $meta_url = route('frontend.termsAndConditions');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "privacy-policy") {
                $meta_url = route('frontend.privacyPolicy');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "why-us") {
                $meta_url = route('frontend.whyUs');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "shipping-unboxing") {
                $meta_url = route('frontend.shippingUnboxing');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "sales-cancellation-policy") {
                $meta_url = route('frontend.salesCancellationPolicy');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
            if ($current_uri == "warranties") {
                $meta_url = route('frontend.warranties');
                return view('frontend.cms_page.page', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cmsData'));
            }
        } else {
            abort(404);
        }
    }
}
