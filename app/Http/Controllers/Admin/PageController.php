<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Page;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Pages')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'pages';
            $title = 'Pages';

            $pages = Page::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.pages.index', compact('menu_1', 'sub_menu', 'active', 'title', 'pages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Pages')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'pages';
            $title = 'Create Page';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.pages.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'slug' => 'required|unique:pages',
            'page_description.*.title' => 'required',
            'page_description.*.meta_title' => 'required',
        ]);

        $res = (new Page())->_store($request);

        if ($res) {
            return redirect()->route('pages.index')->with('success', 'Page Added Successfully');
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Pages')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'pages';
            $title = 'Edit Page';
            $type = 'edit';

            $modal = (new Page())->fetchData($id);

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.pages.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $page = Page::where('id', $id)->first();
        $request->validate([
            'slug' => 'required|unique:pages,slug,' . $page->id,
            'page_description.*.title' => 'required',
            'page_description.*.meta_title' => 'required',
        ]);

        $res = (new Page())->_update($request, $id);

        if ($res) {
            return redirect()->route('pages.index')->with('success', 'Page Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted page.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Pages')) {
            $del = (new Page())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Page())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Page())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Page())->_bulkDelete($request);
    }
}
