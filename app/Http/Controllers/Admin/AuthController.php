<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Zone;
use App\Models\Admin\User;
use App\Models\Admin\Store;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    function index()
    {
        $title = "Sign In";

        $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        return view('admin.auth.login', compact('title', 'stores'));
    }

    function checkSignIn(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'store_id' => 'required'
        ]);

        if (Auth::guard('web')->attempt([
            'email' => $input['email'],
            'password' => $input['password'],
            'status' => getConstant('IS_STATUS_ACTIVE'),
            'is_deleted' => getConstant('IS_NOT_DELETED'),
        ])) {
            $store = Store::select('id', 'name', 'manager', 'address', 'email', 'telephone', 'opening_time', 'closing_time')
                ->where('id', $request->store_id)
                ->first();
            if ($store) {
                session(['store' => $store]);
            }
            return redirect()->route('dashboard.index');
        } else {
            return redirect()->route('admin.signIn')->with('error', 'Credentials do not match!');
        }
    }

    function logout()
    {
        Auth::guard('web')->logout();
        Session::flush();
        return redirect()->route('admin.signIn');
    }

    public function myProfile()
    {
        ### CONST ###
        $title = 'My Profile';

        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get();

        $country_id = (isset(Auth::user()->country_id) && !is_null(Auth::user()->country_id)) ? Auth::user()->country_id : '38';
        $zones = Zone::where('country_id', $country_id) ### country_id = `38` = `Canada` ###
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get();

        $roles = Role::select('id', 'name')->orderBy('id', 'ASC')->get();

        return view('admin.auth.my_profile', compact('title', 'roles', 'countries', 'zones'));
    }

    public function handleProfileUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
        ]);

        $update = (new User())->_update($request, Auth::user()->id);

        if ($update) {
            return back()->with('success', 'Updated your profile successfully.');
        }
    }

    public function changePassword()
    {
        ### CONST ###
        $title = 'Change Password';

        return view('admin.auth.change_password', compact('title'));
    }

    public function handleChangePassword(Request $request)
    {
        // return $request;
        $request->validate([
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required_with:new_password|same:new_password|min:8',
        ]);

        $user_id = Auth::guard('web')->user()->id;
        $user = User::where('id', $user_id)->first();

        if (Hash::check($request->old_password, $user->password)) {
            User::where('id', $user_id)->update(['password' => Hash::make($request->new_password), 'textual_password' => $request->new_password]);
            return redirect()->back()->with('success', 'Password updated successfully.');
        } else {
            return redirect()->back()->with('error', 'The old password field does not match.');
        }
    }
}
