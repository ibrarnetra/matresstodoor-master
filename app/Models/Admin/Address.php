<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Zone;
use App\Models\Admin\Customer;
use App\Models\Admin\Country;

class Address extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function address()
    {
        return $this->hasOne(Customer::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    function _insert($customer_id, $first_name, $last_name, $company = "", $address_1="", $address_2 = "", $city="", $postcode="", $country_id="", $zone_id="", $is_default="", $lat = "0.000", $lng = "0.000", $telephone = null)
    {
        $address = new Address();
        $address->customer_id = $customer_id;
        $address->first_name = $first_name;
        $address->last_name = $last_name;
        $address->telephone = $telephone;
        $address->company = $company;
        $address->lat = $lat;
        $address->lng = $lng;
        $address->address_1 = $address_1;
        $address->address_2 = $address_2;
        $address->city = $city;
        $address->postcode = $postcode;
        $address->country_id = $country_id;
        $address->zone_id = $zone_id;
        $address->save();

        $address_id = $address->id;
        if ($is_default) {
            Customer::where('id', $customer_id)->update([
                'address_id' => $address_id,
            ]);
        }

        return $address_id;
    }

    function _getCustomerAddresses($customer_id)
    {
        return self::with([
            'country' => function ($q) {
                $q->select('id', 'name');
            },
            'country.zones' => function ($q) {
                $q->select('id', 'name', 'country_id');
            },
            'zone' => function ($q) {
                $q->select('id', 'name');
            },
        ])->where('customer_id', $customer_id)->get();
    }

    function _loadAddresses($request)
    {
        $counter = $request->counter;
        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get();
        $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get();
        $view = view('frontend.auth.addresses', compact('counter', 'countries', 'zones'))->render();

        return json_encode(['status' => true, 'data' => $view]);
    }

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _store($request)
    {
        $is_default = isset($request->is_default) && !is_null($request->is_default) ? true : false;
        $lat = isset($request->lat) && !is_null($request->lat) ? $request->lat : "0.0000";
        $lng = isset($request->lng) && !is_null($request->lng) ? $request->lng : "0.0000";

        $address = new Address();
        $address->customer_id = $request->customer_id;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->telephone = $request->telephone;
        $address->company = (isset($request->company) && !is_null($request->company)) ? $request->company : "";
        $address->address_1 = $request->address_1;
        $address->address_2 = (isset($request->address_2) && !is_null($request->address_2)) ? $request->address_2 : "";
        $address->city = $request->city;
        $address->postcode = $request->postcode;
        $address->country_id = $request->country_id;
        $address->zone_id = $request->zone_id;
        $address->lat = $lat;
        $address->lng = $lng;

        $address->save();

        $address_id = $address->id;
        if ($is_default) {
            Customer::where('id', $request->customer_id)->update([
                'address_id' => $address_id,
            ]);
        }
        return $address_id;
    }

    function _update($request, $id)
    {
        $is_default = isset($request->is_default) && !is_null($request->is_default) ? true : false;
        $lat = isset($request->lat) && !is_null($request->lat) ? $request->lat : "0.0000";
        $lng = isset($request->lng) && !is_null($request->lng) ? $request->lng : "0.0000";

        self::where('id', $id)->update([
            'customer_id' => $request->customer_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'telephone' => $request->telephone,
            'company' => (isset($request->company) && !is_null($request->company)) ? $request->company : "",
            'address_1' => $request->address_1,
            'address_2' => (isset($request->address_2) && !is_null($request->address_2)) ? $request->address_2 : "",
            'city' => $request->city,
            'postcode' => $request->postcode,
            'country_id' => $request->country_id,
            'zone_id' => $request->zone_id,
            'lat' => $lat,
            'lng' => $lng,
        ]);

        if ($is_default) {
            Customer::where('id', $request->customer_id)->update([
                'address_id' => $id,
            ]);
        }
        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->delete();
    }
}
