<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Zone;
use App\Models\Admin\Subscriber;
use App\Models\Admin\Review;
use App\Models\Admin\Product;
use App\Models\Admin\Order;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Country;
use App\Models\Admin\Address;

class Customer extends Authenticatable
{
    use HasFactory;

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function default_address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'customer_wishlist');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    function _store($request)
    {
        $customer = new Customer();
        $customer->customer_group_id = (isset($request->customer_group_id) && !is_null($request->customer_group_id)) ? $request->customer_group_id : (new CustomerGroup())->_getDefaultGroupId();
        $customer->store_id = (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : "0";
        $customer->language_id = (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1";
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->telephone = $request->telephone;
        $customer->password = (isset($request->password) && !is_null($request->password)) ? Hash::make($request->password) : Hash::make(random_password(10));
        $customer->created_by = (Auth::guard("web")->user()) ? Auth::guard("web")->user()->id : 0;
        $customer->save();

        $customer_id = $customer->id;

        if ($request->has('address')) {
            foreach ($request->address as $key => $val) {
                $is_default = isset($val['is_default']) && !is_null($val['is_default']) ? true : false;
                $lat = isset($val['lat']) && !is_null($val['lat']) ? $val['lat'] : "0.0000";
                $lng = isset($val['lng']) && !is_null($val['lng']) ? $val['lng'] : "0.0000";
                $company = isset($val['company']) && !is_null($val['company']) ? $val['company'] : "";
                $address_2 = isset($val['address_2']) && !is_null($val['address_2']) ? $val['address_2'] : "";
                (new Address())->_insert($customer_id, $val['first_name'], $val['last_name'], $company, $val['address_1'], $address_2, $val['city'], $val['postcode'], $val['country_id'], $val['zone_id'], $is_default, $lat, $lng, $val['telephone']);
            }
        }

        /**
         * subscribe to newsletter if "Sign up our Newsletter" is checked
         */
        (isset($request->subscribe) && !is_null($request->subscribe) && $request->subscribe == "1") ? (new Subscriber())->_store($request) : "";

        return $customer_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "customer_group_id" => (isset($request->customer_group_id) && !is_null($request->customer_group_id)) ? $request->customer_group_id : (new CustomerGroup())->_getDefaultGroupId(),
            "store_id" => (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : "0",
            "language_id" => (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1",
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "telephone" => $request->telephone,
        ]);

        if (isset($request->password) && !is_null($request->password)) {
            self::where('id', $id)->update([
                "password" => Hash::make($request->password),
            ]);
        }

        if ($request->has('address')) {
            Address::where('customer_id', $id)->delete();
            foreach ($request->address as $key => $val) {
                $is_default = isset($val['is_default']) && !is_null($val['is_default']) ? true : false;
                $lat = isset($val['lat']) && !is_null($val['lat']) ? $val['lat'] : "0.0000";
                $lng = isset($val['lng']) && !is_null($val['lng']) ? $val['lng'] : "0.0000";
                $company = isset($val['company']) && !is_null($val['company']) ? $val['company'] : "";
                $address_2 = isset($val['address_2']) && !is_null($val['address_2']) ? $val['address_2'] : "";
                (new Address())->_insert($id, $val['first_name'], $val['last_name'], $company, $val['address_1'], $address_2, $val['city'], $val['postcode'], $val['country_id'], $val['zone_id'], $is_default, $lat, $lng, $val['telephone']);
            }
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::where('id', $id)->first();
        return array(
            "id" => $query->id,
            "customer_group_id" => $query->customer_group_id,
            "store_id" => $query->store_id,
            "language_id" => $query->language_id,
            "address_id" => $query->address_id,
            "first_name" => $query->first_name,
            "last_name" => $query->last_name,
            "email" => $query->email,
            "telephone" => $query->telephone,
            "status" => $query->status,
            "addresses" => (new Address())->_getCustomerAddresses($id),
        );
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
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
        $view = view('admin.customers.addresses', compact('counter', 'countries', 'zones'))->render();

        return json_encode(['status' => true, 'data' => $view]);
    }

    function _search($request)
    {
        $total_words = str_word_count($request->q);
        $q = $request->q;
        $q1 = $request->q;
        $q2 = $request->q;
        if($total_words>1)
        {
            $cust = explode(" ",$request->q);
            $q1 = "%" . $cust[0]. "%";
            $q2 = "%" . $cust[1]. "%";
        }
        else{
            $q = "%" . $request->q . "%";
        }
      
        $customers = self::where('is_deleted', getConstant('IS_NOT_DELETED'))
        ->when($total_words =='1', function ($query) use($q) {
            return  $query->where(function ($query) use ($q) {
                $query->where('first_name', 'like', $q)->orWhere('last_name', 'like', $q);
            });
        })
        ->when($total_words>'1', function ($query) use($q1,$q2) {
            return  $query->where(function ($query) use ($q1,$q2) {
                $query->where('first_name', 'like', $q1)->where('last_name', 'like', $q2);
            });
        })->get(['id', 'first_name', 'last_name', 'email', 'telephone']);

        $arr = [];
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                $temp['id'] = $customer->id;
                $temp['text'] = $customer->first_name . ' ' . $customer->last_name;
                $arr[] = $temp;
            }
        }

        return json_encode(["status" => true, "search" => $arr, 'data' => $customers]);
    }

    function _getCustomerAddresses($request)
    {
        $res = ['status' => false, 'data' => ''];
        $addresses =  Address::with([
            'country' => function ($q) {
                $q->select('id', 'name');
            },
            'zone' => function ($q) {
                $q->select('id', 'name');
            }
        ])->where('customer_id', $request->id)->get();

        if ($addresses) {
            $res['status'] = true;
            $res['data'] = $addresses;
        }
        return json_encode($res);
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            ### INIT QUERY ###
            $customers = self::with([
                'customer_group',
                'customer_group.eng_description',
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            ### INIT DATATABLE ###
            return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('customer_group_name', function ($row) {
                    $customer_group_name = 'N/A';
                    if (isset($row->customer_group) && !is_null($row->customer_group) && $row->customer_group != "") {
                        $customer_group_name = $row->customer_group->eng_description->name;
                    }
                    return $customer_group_name;
                })
                ->addColumn('date_added', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('customers.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Customers')) {
                        $action .= '<a href="' . route('customers.edit', ['id' => $row->id]) . '" class="btn btn-sm btn-icon btn-active-light-primary"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Customers')) {
                        $param = "'" . route('customers.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="btn btn-sm btn-icon btn-active-light-primary"
                                        onclick="deleteData(' . $param . ')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns(['checkbox', 'customer_name', 'date_added', 'status', 'action'])
                ->make(true);
        }
    }
}
