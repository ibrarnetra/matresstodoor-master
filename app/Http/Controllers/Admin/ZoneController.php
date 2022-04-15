<?php

namespace App\Http\Controllers\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Models\Admin\Zone;
use App\Imports\ZoneImport;
use App\Http\Controllers\Controller;

class ZoneController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Zones')) {
        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'localization';
        $active = 'zones';
        $title = 'States';

        $zones = Zone::with([
            'country' => function ($q) {
                $q->select('id', 'name');
            }
        ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('admin.zones.index', compact('menu_1', 'sub_menu', 'active', 'title', 'zones'));
        }
        else{
            return redirect()->route('dashboard.index');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone)
    {
        //
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);
        ### Dumping Excel Data to JSON ###
        // return Excel::toArray(new ZoneImport(), request()->file('file'));
        ### Importing Excel to DB ###
        $res = Excel::import(new ZoneImport(), request()->file('file'));
        if ($res) {
            return back()->with('success', 'Zones Imported Successfully');
        }
    }

    public function dataTable(Request $request)
    {
        return (new Zone())->_dataTable($request);
    }

    public function search(Request $request)
    {
        return (new Zone())->_search($request);
    }

    public function getZones(Request $request)
    {
        return (new Zone())->_getZones($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Zone())->_bulkDelete($request);
    }
}
