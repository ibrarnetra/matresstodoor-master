<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Zone;
use App\Models\Admin\WeightClass;
use App\Models\Admin\TaxClass;
use App\Models\Admin\Setting;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\LengthClass;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Currency;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function edit()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Settings')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'settings';
            $title = 'Edit Setting';
            $type = 'edit';

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $country_id = isset($modal['config_country_id']) ? $modal['config_country_id'] : 38;
            $zones = Zone::where('country_id', $country_id) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $customer_groups = CustomerGroup::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $length_classes = LengthClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $weight_classes = WeightClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $tax_classes = TaxClass::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            $modal = (new Setting())->_show();

            return view('admin.settings.form', compact('menu_1', 'active', 'title', 'type', 'modal', 'countries', 'zones', 'currencies', 'tax_classes', 'order_statuses', 'customer_groups', 'length_classes', 'weight_classes'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request)
    {
        // return $request;
        $request->validate([
            'data.config_meta_title' => 'required',
        ]);

        $res = (new Setting())->_update($request);

        if ($res) {
            return redirect()->route('settings.edit')->with('success', 'Setting Updated Successfully');
        }
    }
}
