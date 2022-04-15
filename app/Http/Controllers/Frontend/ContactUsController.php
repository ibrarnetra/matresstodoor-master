<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Admin\Store;
use App\Models\Admin\Page;
use App\Mail\Enquiry;
use App\Mail\ContactResponse;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    public function showContactUs()
    {
        $cmsData = (new Page('en'))->getCmsPage('contact-us');

        $title = ($cmsData) ? $cmsData->title : 'Contact Us';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Contact Us';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Contact Us";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Contact Us";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.showContactUs');

        return view('frontend.contact_us.contact_us', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url'));
    }

    public function handleContactUs(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'enquiry' => 'required',
        ]);

        if (!validateCaptchaToken($request->captcha)['status']) {
            return redirect()->back()->with('error', 'Captcha error! try again later or contact site admin.');
        }

        $default_store_email = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))->first();
        ### SEND EMAIL TO RECEIVER ###
        Mail::to($default_store_email)->send(new Enquiry($request->name, $request->email, $request->enquiry));
        ### SEND EMAIL TO SENDER ###
        Mail::to($request->email)->send(new ContactResponse($request->enquiry));

        return back()->with('success', 'Thank you for contacting us, our support will get back to you shortly.');
    }
}
