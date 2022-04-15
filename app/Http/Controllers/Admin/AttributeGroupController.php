<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Language;
use App\Models\Admin\AttributeGroup;
use App\Http\Controllers\Controller;

class AttributeGroupController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Attribute-Groups')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attribute-groups';
            $title = 'Attribute Groups';

            $attribute_groups = AttributeGroup::with([
                'eng_description' => function ($q) {
                    $q->select('attribute_group_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.attribute_groups.index', compact('menu_1', 'sub_menu', 'active', 'title', 'attribute_groups'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Attribute-Groups')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attribute-groups';
            $title = 'Create Attribute Group';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))->where('status', getConstant('IS_STATUS_ACTIVE'))->get();

            return view('admin.attribute_groups.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        //        return $request;
        $request->validate([
            'attribute_group_description.*.name' => 'required',
        ]);

        $inserted = (new AttributeGroup())->_store($request);

        if ($inserted) {
            return redirect()->route('attribute-groups.index')->with('success', 'Attribute Group Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Attribute-Groups')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $sub_menu = 'attribute_manager';
            $active = 'attribute-groups';
            $title = 'Edit Attribute Group';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))->where('status', getConstant('IS_STATUS_ACTIVE'))->get();

            $modal = (new AttributeGroup())->fetchData($id);

            return view('admin.attribute_groups.form', compact('menu_1', 'sub_menu', 'active', 'title', 'modal', 'type', 'id', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        //        return $request;
        $request->validate([
            'attribute_group_description.*.name' => 'required',
        ]);

        $update = (new AttributeGroup())->_update($request, $id);

        if ($update) {
            return redirect()->route('attribute-groups.index')->with('success', 'Attribute Group Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted attribute group.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Attribute-Groups')) {
            $del = (new AttributeGroup())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new AttributeGroup())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new AttributeGroup())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new AttributeGroup())->_bulkDelete($request);
    }
}
