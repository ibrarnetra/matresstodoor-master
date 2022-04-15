<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Slider;
use App\Models\Admin\Slide;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Sliders')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'sliders';
            $title = 'Sliders';

            $sliders = Slider::with([
                'slides' => function ($q) {
                    $q->orderBy('sort_order', 'ASC');
                }
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('sort_order', 'ASC')
                ->get();

            return view('admin.sliders.index', compact('menu_1', 'sub_menu', 'active', 'title', 'sliders'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Sliders')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'sliders';
            $title = 'Create Slider';
            $type = 'create';

            return view('admin.sliders.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'sort_order' => 'required',
            'slides.*' => 'required',
            'slides.*.image' => 'required',
            'slides.*.sort_order' => 'required',
        ]);

        $res = (new Slider())->_store($request);

        if ($res) {
            return redirect()->route('sliders.index')->with('success', 'Slider Added Successfully');
        }
    }

    public function show($id)
    {
        return (new Slider())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Sliders')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'sliders';
            $title = 'Edit Slider';
            $type = 'edit';

            $modal = (new SliderController())->show($id);

            return view('admin.sliders.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'sort_order' => 'required',
            'slides.*' => 'required',
        ]);

        $res = (new Slider())->_update($request, $id);

        if ($res) {
            return redirect()->route('sliders.index')->with('success', 'Slider Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted Slider.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Sliders')) {
            $del = (new Slider())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Slider())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Slider())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Slider())->_bulkDelete($request);
    }

    public function deleteSlide($id)
    {
        // return $id;
        return (new Slide())->_deleteSlide($id);
    }
}
