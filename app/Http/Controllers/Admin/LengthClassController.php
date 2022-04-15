<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\Admin\LengthClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LengthClassController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Length-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'length-classes';
            $title = 'Length Classes';

            $length_classes = LengthClass::with([
                'eng_description' => function ($q) {
                    $q->select('length_class_id', 'language_id', 'title', 'unit');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.length_classes.index', compact('menu_1', 'sub_menu', 'active', 'title', 'length_classes'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Length-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'length-classes';
            $title = 'Create Length Class';
            $type = 'create';
            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            return view('admin.length_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'length_class_description.*.title' => 'required|max:32',
            'length_class_description.*.unit' => 'required|max:4',
        ]);

        $res = (new LengthClass())->_store($request);

        if ($res) {
            return redirect()->route('length-classes.index')->with('success', 'Length Class Added Successfully');
        }
    }

    public function show($id)
    {
        return (new LengthClass())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Length-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'length-classes';
            $title = 'Edit Length Class';
            $type = 'edit';
            $modal = (new LengthClass())->fetchData($id);
            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            return view('admin.length_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'length_class_description.*.title' => 'required|max:32',
            'length_class_description.*.unit' => 'required|max:4',
        ]);

        $res = (new LengthClass())->_update($request, $id);

        if ($res) {
            return redirect()->route('length-classes.index')->with('success', 'Length Class Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted length class.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Length-Classes')) {
            $del = (new LengthClass())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new LengthClass())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new LengthClass())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new LengthClass())->_bulkDelete($request);
    }
}
