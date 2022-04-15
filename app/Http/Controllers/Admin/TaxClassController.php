<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\TaxRate;
use App\Models\Admin\TaxClass;
use App\Http\Controllers\Controller;

class TaxClassController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Tax-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-classes';
            $title = 'Tax Classes';

            $tax_classes = TaxClass::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.tax_classes.index', compact('menu_1', 'sub_menu', 'active', 'title', 'tax_classes', 'child_menu'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Tax-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-classes';
            $title = 'Create Tax Class';
            $type = 'create';

            $tax_rates = TaxRate::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            return view('admin.tax_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'child_menu', 'tax_rates'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $res = (new TaxClass())->_store($request);

        if ($res) {
            return redirect()->route('tax-classes.index')->with('success', 'Tax Rate Added Successfully');
        }
    }

    public function show($id)
    {
        return (new TaxClass())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Tax-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-classes';
            $title = 'Edit Tax Class';
            $type = 'edit';

            $modal = (new TaxClassController())->show($id);
            $tax_rates = TaxRate::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            return view('admin.tax_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'child_menu', 'tax_rates'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $res = (new TaxClass())->_update($request, $id);

        if ($res) {
            return redirect()->route('tax-classes.index')->with('success', 'Tax Rate Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted tax class.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Tax-Classes')) {
            $del = (new TaxClass())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new TaxClass())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new TaxClass())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new TaxClass())->_bulkDelete($request);
    }

    public function getApplicableTaxClass(Request $request)
    {
        $res = ['status' => false, 'data' => 'There is no Tax applicable for this Country and State, please provide Tax information for the selected Country and State.'];

        if (isset($request->country_id) && !is_null($request->country_id) && $request->country_id != "" && isset($request->zone_id) && !is_null($request->zone_id) && $request->zone_id != "") {
            $country_id = $request->input('country_id');
            $zone_id = $request->input('zone_id');


            $tax_class = (new TaxClass())->_getApplicableTaxClass($country_id, $zone_id);
            if ($tax_class) {
                $res['status'] = true;
                $res['data'] = "";
                $res['tax_class'] = $tax_class;
            }
        }

        return json_encode($res);
    }
}
