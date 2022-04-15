<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Page;
use App\Models\Admin\Customer;
use App\Mail\ForgotPassword;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signIn()
    {
        $cmsData = (new Page('en'))->getCmsPage('sign-in');

        $title = ($cmsData) ? $cmsData->title : 'Sign In';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Sign In';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Sign In";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Sign In";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.signIn');

        return view('frontend.auth.auth', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url'));
    }

    public function handleSignIn(Request $request)
    {
        // return $request;
        $input = $request->all();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::guard('frontend')->attempt([
            'email' => $input['email'],
            'password' => $input['password'],
            'status' => getConstant('IS_STATUS_ACTIVE'),
            'is_deleted' => getConstant('IS_NOT_DELETED'),
        ])) {
            return redirect()->route('frontend.home');
        } else {
            return redirect()->route('frontend.signIn')->with('error', 'Credentials do not match!');
        }
    }

    public function handleSignUp(Request $request)
    {
        // return $request;
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'password' => 'required|min:8|confirmed',
        ]);

        $inserted = (new Customer())->_store($request);

        if ($inserted) {
            return redirect()->route('frontend.signIn')->with('success', 'You have successfully created an account.');
        }
    }

    public function logout()
    {
        Auth::guard('frontend')->logout();
        Session::flush();
        return redirect()->route('frontend.signIn');
    }

    public function handleUpdate(Request $request, $id)
    {
        $customer = Customer::where('id', $id)->first();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
        ]);

        $update = (new Customer())->_update($request, $id);

        if ($update) {
            return redirect()->back()->with('success', 'Customer Account Information Updated Successfully.');
        }
    }

    public function forgotPassword()
    {
        $cmsData = (new Page('en'))->getCmsPage('forgot-password');

        $title = ($cmsData) ? $cmsData->title : 'Forgot Password';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Forgot Password';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Forgot Password";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Forgot Password";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.forgotPassword');

        return view('frontend.auth.forgot_password', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url'));
    }

    public function handleForgotPassword(Request $request)
    {
        // return $request;
        $new_pass = random_password(10);
        Customer::where('email', $request->email)->update(['password' => Hash::make($new_pass)]);
        Mail::to($request->email)->send(new ForgotPassword($new_pass));

        return redirect()->back()->with('success', 'New password has been e-mailed to you.');
    }
}
