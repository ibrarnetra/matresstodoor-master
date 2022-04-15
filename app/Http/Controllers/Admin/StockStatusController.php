<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\StockStatus;
use App\Http\Controllers\Controller;

class StockStatusController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Stock-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stock-statuses';
            $title = 'Stock Statuses';

            $stock_statuses = StockStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.stock_statuses.index', compact('menu_1', 'sub_menu', 'active', 'title', 'stock_statuses'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Stock-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stock-statuses';
            $title = 'Create Stock Status';
            $type = 'create';
            return view('admin.stock_statuses.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $res = (new StockStatus())->_store($request);

        if ($res) {
            return redirect()->route('stock-statuses.index')->with('success', 'Stock Status Added Successfully');
        }
    }

    public function show($id)
    {
        return (new StockStatus())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Stock-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stock-statuses';
            $title = 'Edit Stock Status';
            $type = 'edit';
            $modal = (new StockStatusController())->show($id);
            return view('admin.stock_statuses.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $res = (new StockStatus())->_update($request, $id);

        if ($res) {
            return redirect()->route('stock-statuses.index')->with('success', 'Stock Status Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted stock status.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Stock-Statuses')) {
            $del = (new StockStatus())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new StockStatus())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new StockStatus())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new StockStatus())->_bulkDelete($request);
    }
}
