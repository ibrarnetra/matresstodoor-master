<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Order-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'order-statuses';
            $title = 'Order Statuses';


            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'ASC')
                ->get();


            return view('admin.order_statuses.index', compact('menu_1', 'sub_menu', 'active', 'title', 'order_statuses'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Order-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'order-statuses';
            $title = 'Create Order Status';
            $type = 'create';
            return view('admin.order_statuses.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $res = (new OrderStatus())->_store($request);

        if ($res) {
            return redirect()->route('order-statuses.index')->with('success', 'Order Status Added Successfully');
        }
    }

    public function show($id)
    {
        return (new OrderStatus())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Order-Statuses')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'order-statuses';
            $title = 'Edit Order Status';
            $type = 'edit';
            $modal = (new OrderStatusController())->show($id);
            return view('admin.order_statuses.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $res = (new OrderStatus())->_update($request, $id);

        if ($res) {
            return redirect()->route('order-statuses.index')->with('success', 'Order Status Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted order status.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Order-Statuses')) {
            $del = (new OrderStatus())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new OrderStatus())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new OrderStatus())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new OrderStatus())->_bulkDelete($request);
    }
}
