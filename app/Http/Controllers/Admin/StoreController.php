<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Store;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Stores')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stores';
            $title = 'Stores';

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->paginate(10);

            return view('admin.stores.index', compact('menu_1', 'sub_menu', 'active', 'title', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Stores')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stores';
            $title = 'Create Store';
            $type = 'create';

            return view('admin.stores.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'manager' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/'
        ]);

        $res = (new Store())->_store($request);

        if ($res) {
            return redirect()->route('stores.index')->with('success', 'Store Added Successfully');
        }
    }

    public function show($id)
    {
        return (new Store())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Stores')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'stores';
            $title = 'Edit Store';
            $type = 'edit';

            $modal = (new StoreController())->show($id);

            return view('admin.stores.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'manager' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/'
        ]);

        $res = (new Store())->_update($request, $id);

        if ($res) {
            return redirect()->route('stores.index')->with('success', 'Store Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted store.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Stores')) {
            $del = (new Store())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Store())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Store())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Store())->_bulkDelete($request);
    }
}
