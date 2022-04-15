<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Language;
use App\Models\Admin\CustomerGroup;
use App\Http\Controllers\Controller;

class CustomerGroupController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Customer-Groups')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customer-groups';
            $title = 'Customer Groups';

            $customer_groups = CustomerGroup::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.customer_groups.index', compact('menu_1', 'active', 'title', 'customer_groups'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Customer-Groups')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customer-groups';
            $title = 'Create Customer Group';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.customer_groups.form', compact('menu_1', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'customer_group.*.name' => 'required',
            'customer_group.*.description' => 'required',
        ]);

        $inserted = (new CustomerGroup())->_store($request);

        if ($inserted) {
            return redirect()->route('customer-groups.index')->with('success', 'Customer Group Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Customer-Groups')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'customer-groups';
            $title = 'Edit Customer Group';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))->where('status', getConstant('IS_STATUS_ACTIVE'))->get();

            $modal = (new CustomerGroup())->fetchData($id);

            return view('admin.customer_groups.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        //                return $request;
        $request->validate([
            'customer_group.*.name' => 'required',
            'customer_group.*.description' => 'required',
        ]);

        $update = (new CustomerGroup())->_update($request, $id);

        if ($update) {
            return redirect()->route('customer-groups.index')->with('success', 'Customer Group Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted customer group.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Customer-Groups')) {
            $del = (new CustomerGroup())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new CustomerGroup())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new CustomerGroup())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new CustomerGroup())->_bulkDelete($request);
    }
}
