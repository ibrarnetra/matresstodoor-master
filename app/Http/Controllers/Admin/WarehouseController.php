<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Admin\Store;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Language;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    public function index()
    {
        ### CONST ###
        $menu_1 = 'catalog';
        $active = 'warehouses';
        $title = 'Warehouses';

        $warehouses = Warehouse::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.warehouses.index', compact('menu_1', 'active', 'title', 'warehouses'));
    }

    public function create()
    {
        ### CONST ###
        $menu_1 = 'catalog';
        $active = 'warehouses';
        $title = 'Create Warehouse';
        $type = 'create';

       
        return view('admin.warehouses.form', compact('menu_1', 'active', 'title', 'type'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
        ]);
       

        $inserted = (new Warehouse())->_store($request);

        if ($inserted) {
            return redirect()->route('warehouses.index')->with('success', 'Manufacturer Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        ### CONST ###
        $menu_1 = 'catalog';
        $active = 'warehouses';
        $title = 'Edit Warehouse';
        $type = 'edit';
      

        $modal = (new Warehouse())->fetchData($id);
     
    
        return view('admin.warehouses.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id'));
    }

    public function update(Request $request, $id)
    {
        //        return $request;
        $request->validate([
            'name' => 'required',
        ]);

        $update = (new Warehouse())->_update($request, $id);

        if ($update) {
            return redirect()->route('warehouses.index')->with('success', 'Warehouse Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted warehouse.'];
        $del = (new Warehouse())->_destroy($id);

        if (!$del) {
            $res["status"] = false;
            $res["data"] = "Error.";
        }
        return json_encode($res);
    }

    public function search(Request $request)
    {
    }

  
    public function dataTable(Request $request)
    {
        return (new Warehouse())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Warehouse())->_bulkDelete($request);
    }

  

  
}
