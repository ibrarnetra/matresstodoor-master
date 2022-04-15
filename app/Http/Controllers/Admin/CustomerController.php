<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Language;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Customer;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Customers')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customers';
            $title = 'Customers';

            return view('admin.customers.index', compact('menu_1', 'active', 'title'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Customers')) {

            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customers';
            $title = 'Create Customer';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $customer_groups = CustomerGroup::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return view('admin.customers.form', compact('menu_1', 'active', 'title', 'type', 'languages', 'customer_groups', 'countries'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,is_deleted,' . getConstant('IS_NOT_DELETED'),
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'password' => 'required|min:8|confirmed',
        ]);

        $inserted = (new Customer())->_store($request);

        if ($inserted) {
            return redirect()->route('customers.index')->with('success', 'Customer Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Customers')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customers';
            $title = 'Edit Customer';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))->where('status', getConstant('IS_STATUS_ACTIVE'))->get();
            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $customer_groups = CustomerGroup::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $modal = (new Customer())->fetchData($id);

            return view('admin.customers.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id', 'languages', 'customer_groups', 'countries'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $customer = Customer::where('id', $id)->first();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id . ',id,is_deleted,' . getConstant('IS_NOT_DELETED'),
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
        ]);

        $update = (new Customer())->_update($request, $id);

        if ($update) {
            return redirect()->route('customers.index')->with('success', 'Customer Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted customer.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Customers')) {
            $del = (new Customer())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Customer())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Customer())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Customer())->_bulkDelete($request);
    }

    public function loadAddresses(Request $request)
    {
        return (new Customer())->_loadAddresses($request);
    }

    public function search(Request $request)
    {
        return (new Customer())->_search($request);
    }

    public function getCustomerAddresses(Request $request)
    {
        return (new Customer())->_getCustomerAddresses($request);
    }

    public function ajaxSubmit(Request $request)
    {
        $success = ['status' => true, 'data' => 'Success', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'customer_group_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'address.*.first_name' => 'required',
            'address.*.last_name' => 'required',
            'address.*.telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'address.*.address_1' => 'required',
            'address.*.city' => 'required',
            'address.*.postcode' => 'required',
            'address.*.country_id' => 'required',
            'address.*.zone_id' => 'required',
        ]);

        if ($validator->fails()) {
            $err['status'] = false;
            $err['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $error_res = generateValidErrorResponse($validator->errors()->getMessages());
            $err['error'] = $error_res;
            return sendResponse($err);
        }

        $res = (new Customer())->_store($request);

        if ($res) {
            $success['customer_id'] = $res;
            return sendResponse($success);
        }
    }
}
