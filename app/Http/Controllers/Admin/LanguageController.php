<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'localization';
        $active = 'languages';
        $title = 'Languages';

        $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get(['id', 'name', 'code', 'status', 'is_deleted']);

        return view('admin.languages.index', compact('menu_1', 'sub_menu', 'active', 'title', 'languages'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataTable(Request $request)
    {
        return (new Language())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Language())->_bulkDelete($request);
    }
}
