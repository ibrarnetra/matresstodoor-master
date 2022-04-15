<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Slide;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    function _store($request)
    {
        $slider = new Slider();
        $slider->name = $request->name;
        $slider->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";
        $slider->save();

        $slider_id = $slider->id;

        if ($request->has('slides')) {
            (new Slide())->_insert($slider_id, $request->slides);
        }

        self::where('id', $slider_id)->update([
            'slug' => Str::slug($request->name) . "-" . $slider_id,
        ]);

        return $slider_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "name" => $request->name,
            "sort_order" => $request->sort_order,
        ]);

        if ($request->has('slides')) {
            (new Slide())->_insert($id, $request->slides);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function _dataTable($request)
    {
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }

    function _show($id)
    {
        return self::select('id', 'slug', 'name', 'sort_order', 'status', 'is_deleted')->with([
            'slides' => function ($q) {
                $q->select('id', 'slider_id', 'image', 'sort_order')
                    ->orderBy('sort_order', 'ASC');
            }
        ])
            ->where('id', $id)->first();
    }
}
