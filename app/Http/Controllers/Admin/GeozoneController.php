<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Zone;
use App\Models\Admin\Geozone;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;

class GeozoneController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Geozones')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'geozones';
            $title = 'Geo Zones';

            $geozones = Geozone::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.geozones.index', compact('menu_1', 'sub_menu', 'active', 'title', 'geozones', 'child_menu'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Geozones')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'geozones';
            $title = 'Create Geo Zone';
            $type = 'create';

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.geozones.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'child_menu', 'countries', 'zones'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $res = (new Geozone())->_store($request);

        if ($res) {
            return redirect()->route('geozones.index')->with('success', 'Geo Zone Added Successfully');
        }
    }

    public function show($id)
    {
        return (new Geozone())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Geozones')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $child_menu = 'taxes';
            $active = 'geozones';
            $title = 'Edit Geo Zone';
            $type = 'edit';

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $modal = (new GeozoneController())->show($id);

            return view('admin.geozones.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'child_menu', 'countries', 'zones'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $res = (new Geozone())->_update($request, $id);

        if ($res) {
            return redirect()->route('geozones.index')->with('success', 'Geo Zone Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted geo zone.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Geozones')) {
            $del = (new Geozone())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Geozone())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Geozone())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Geozone())->_bulkDelete($request);
    }
}
