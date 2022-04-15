<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Currencies')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'currencies';
            $title = 'Currencies';

            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.currencies.index', compact('menu_1', 'sub_menu', 'active', 'title', 'currencies'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Currencies')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'currencies';
            $title = 'Create Currency';
            $type = 'create';
            return view('admin.currencies.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'code' => 'required',
        ]);

        $res = (new Currency())->_store($request);

        if ($res) {
            return redirect()->route('currencies.index')->with('success', 'Currency Added Successfully');
        }
    }

    public function show($id)
    {
        return (new Currency())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Currencies')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'currencies';
            $title = 'Edit Currency';
            $type = 'edit';
            $modal = (new CurrencyController())->show($id);
            return view('admin.currencies.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'code' => 'required',
        ]);

        $res = (new Currency())->_update($request, $id);

        if ($res) {
            return redirect()->route('currencies.index')->with('success', 'Currency Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted attribute.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Currencies')) {
            $del = (new Currency())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Currency())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Currency())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Currency())->_bulkDelete($request);
    }
}
