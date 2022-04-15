<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\WeightClass;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;

class WeightClassController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Weight-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'weight-classes';
            $title = 'Weight Classes';

            $weight_classes = WeightClass::with([
                'eng_description' => function ($q) {
                    $q->select('weight_class_id', 'language_id', 'title', 'unit');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.weight_classes.index', compact('menu_1', 'sub_menu', 'active', 'title', 'weight_classes'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Weight-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'weight-classes';
            $title = 'Create Weight Class';
            $type = 'create';
            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            return view('admin.weight_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight_class_description.*.title' => 'required|max:32',
            'weight_class_description.*.unit' => 'required|max:4',
        ]);

        $res = (new WeightClass())->_store($request);

        if ($res) {
            return redirect()->route('weight-classes.index')->with('success', 'Weight Class Added Successfully');
        }
    }

    public function show($id)
    {
        return (new WeightClass())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Weight-Classes')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'weight-classes';
            $title = 'Edit Weight Class';
            $type = 'edit';
            $modal = (new WeightClass())->fetchData($id);
            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            return view('admin.weight_classes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'weight_class_description.*.title' => 'required|max:32',
            'weight_class_description.*.unit' => 'required|max:4',
        ]);

        $res = (new WeightClass())->_update($request, $id);

        if ($res) {
            return redirect()->route('weight-classes.index')->with('success', 'Weight Class Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted weight class.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Weight-Classes')) {
            $del = (new WeightClass())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new WeightClass())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new WeightClass())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new WeightClass())->_bulkDelete($request);
    }
}
