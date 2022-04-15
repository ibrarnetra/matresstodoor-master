<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Language;
use App\Models\Admin\AttributeGroup;
use App\Models\Admin\Attribute;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Attributes')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attributes';
            $title = 'Attributes';

            $attributes = Attribute::with([
                'eng_description' => function ($q) {
                    $q->select('attribute_id', 'language_id', 'name');
                },
                'attribute_group' => function ($q) {
                    $q->select('id');
                },
                'attribute_group.eng_description' => function ($q) {
                    $q->select('attribute_group_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.attributes.index', compact('menu_1', 'sub_menu', 'active', 'title', 'attributes'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Attributes')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attributes';
            $title = 'Create Attribute';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $attribute_groups = AttributeGroup::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            return view('admin.attributes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages', 'attribute_groups'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'attribute_description.*.name' => 'required',
            'attribute_group_id' => 'required',
        ]);

        $inserted = (new Attribute())->_store($request);

        if ($inserted) {
            return redirect()->route('attributes.index')->with('success', 'Attribute Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Attributes')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attributes';
            $title = 'Edit Attribute';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $attribute_groups = AttributeGroup::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            $modal = (new Attribute())->fetchData($id);

            return view('admin.attributes.form', compact('menu_1', 'sub_menu', 'active', 'title', 'modal', 'type', 'id', 'languages', 'attribute_groups'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        //        return $request;
        $request->validate([
            'attribute_description.*.name' => 'required',
            'attribute_group_id' => 'required',
        ]);

        $update = (new Attribute())->_update($request, $id);

        if ($update) {
            return redirect()->route('attributes.index')->with('success', 'Attribute Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted attribute.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Attributes')) {
            $del = (new Attribute())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Attribute())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Attribute())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Attribute())->_bulkDelete($request);
    }
}
