<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\TaxRate;
use App\Models\Admin\Geozone;
use App\Models\Admin\CustomerGroup;
use App\Http\Controllers\Controller;

class TaxRateController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Tax-Rates')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-rates';
            $title = 'Tax Rates';

            $tax_rates = TaxRate::with([
                'geozone' => function ($q) {
                    $q->select('id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.tax_rates.index', compact('menu_1', 'sub_menu', 'active', 'title', 'tax_rates', 'child_menu'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Tax-Rates')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-rates';
            $title = 'Create Tax Rate';
            $type = 'create';

            $customer_groups = CustomerGroup::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            $geozones = Geozone::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return view('admin.tax_rates.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'child_menu', 'customer_groups', 'geozones'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'rate' => 'required',
        ]);

        $res = (new TaxRate())->_store($request);

        if ($res) {
            return redirect()->route('tax-rates.index')->with('success', 'Tax Rate Added Successfully');
        }
    }

    public function show($id)
    {
        return (new TaxRate())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Tax-Rates')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'tax-rates';
            $title = 'Edit Tax Rate';
            $type = 'edit';

            $geozones = Geozone::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $customer_groups = CustomerGroup::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $modal = (new TaxRateController())->show($id);

            return view('admin.tax_rates.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'child_menu', 'geozones', 'customer_groups'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'rate' => 'required',
        ]);

        $res = (new TaxRate())->_update($request, $id);

        if ($res) {
            return redirect()->route('tax-rates.index')->with('success', 'Tax Rate Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted tax rate.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Tax-Rates')) {
            $del = (new TaxRate())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new TaxRate())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new TaxRate())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new TaxRate())->_bulkDelete($request);
    }
}
