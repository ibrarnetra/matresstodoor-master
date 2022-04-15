<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'localization';
        $active = 'store-locations';
        $title = 'Store Locations';

        $locations = Location::where('is_deleted', getConstant('IS_NOT_DELETED'))->orderBy('id', 'DESC')->get();

        return view('admin.store_locations.index', compact('menu_1', 'sub_menu', 'active', 'title', 'locations'));
    }

    public function create()
    {
        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'localization';
        $active = 'store-locations';
        $title = 'Create Store Location';
        $type = 'create';

        return view('admin.store_locations.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
        ]);

        $res = (new Location())->_store($request);

        if ($res) {
            return redirect()->route('store-locations.index')->with('success', 'Store Location Added Successfully');
        }
    }

    public function show($id)
    {
        return (new Location())->_show($id);
    }

    public function edit($id)
    {
        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'localization';
        $active = 'store-locations';
        $title = 'Edit Store Location';
        $type = 'edit';

        $modal = (new LocationController())->show($id);

        return view('admin.store_locations.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'telephone' => 'required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
        ]);

        $res = (new Location())->_update($request, $id);

        if ($res) {
            return redirect()->route('store-locations.index')->with('success', 'Store Location Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted location.'];
        $del = (new Location())->_destroy($id);

        if (!$del) {
            $res["status"] = false;
            $res["data"] = "Error.";
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Location())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Location())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Location())->_bulkDelete($request);
    }
}
