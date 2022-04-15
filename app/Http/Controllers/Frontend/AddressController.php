<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Admin\Zone;
use App\Models\Admin\Country;
use App\Models\Admin\Address;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $title = 'New Address';
        $meta_title = 'New Address';
        $meta_description = "New Address";
        $meta_keyword = "New Address";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('addresses.create');
        $type = "create";

        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        return view('frontend.dashboard.address', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'countries', 'zones', 'type'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'customer_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'address_1' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'country_id' => 'required',
            'zone_id' => 'required',
        ]);

        $res = (new Address())->_store($request);
        if ($res) {
            return redirect()->route('frontend.dashboard', ['page' => 'address-book'])->with('success', 'Customer address created successfully.');
        }
    }

    public function show($id)
    {
        return (new Address())->_show($id);
    }

    public function edit($id)
    {
        $title = 'Edit Address';
        $meta_title = 'Edit Address';
        $meta_description = "Edit Address";
        $meta_keyword = "Edit Address";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('addresses.edit', ['id' => $id]);
        $type = "edit";

        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $modal = (new AddressController())->show($id);

        return view('frontend.dashboard.address', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'countries', 'zones', 'modal', 'type', 'id'));
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'customer_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'address_1' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'country_id' => 'required',
            'zone_id' => 'required',
        ]);

        $res = (new Address())->_update($request, $id);
        if ($res) {
            return redirect()->route('frontend.dashboard', ['page' => 'address-book'])->with('success', 'Customer address updated successfully.');
        }
    }

    public function destroy($id)
    {
        $del = (new Address())->_destroy($id);

        if ($del) {
            return redirect()->route('frontend.dashboard', ['page' => 'address-book'])->with('success', 'Customer address deleted successfully.');
        } else {
            return redirect()->route('frontend.dashboard', ['page' => 'address-book'])->with('error', 'There was an error in processing your request.');
        }
    }

    public function loadAddresses(Request $request)
    {
        return (new Address())->_loadAddresses($request);
    }
}
