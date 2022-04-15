<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Slider;

class Slide extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    function _insert($slider_id, $slides)
    {
        if (count($slides) > 0) {
            foreach ($slides as $slide) {

                if (isset($slide['image']) && !is_null($slide['image'])) {
                    if (isset($slide['old_image']) && !is_null($slide['old_image'])) {
                        ### REMOVE ORIGINAL ###
                        if (file_exists(storage_path('app/public/slider_images/' . $slide['old_image']))) {
                            unlink(storage_path('app/public/slider_images/' . $slide['old_image']));
                        }
                        ### REMOVE RESIZED ###
                        if (file_exists(storage_path('app/public/slider_images/150x150/' . $slide['old_image']))) {
                            unlink(storage_path('app/public/slider_images/150x150/' . $slide['old_image']));
                        }
                    }


                    if (isset($slide['id']) && !is_null($slide['id'])) {
                        self::where('id', $slide['id'])->update([
                            "image" => saveImage($slide['image'], 'slider_images'),
                        ]);
                    }
                }

                if (isset($slide['id']) && !is_null($slide['id'])) {
                    self::where('id', $slide['id'])->update([
                        "sort_order" => $slide['sort_order'],
                    ]);
                } else {
                    Slide::create([
                        'slider_id' => $slider_id,
                        'image' => saveImage($slide['image'], 'slider_images'),
                        'sort_order' => $slide['sort_order'],
                    ]);
                }
            }
        }

        return $slider_id;
    }

    function _deleteSlide($id)
    {
        $res = ['status' => false, 'data' => 'Unable to process your request.'];

        $slide = self::where('id', $id)->first();

        if ($slide) {
            ### REMOVE ORIGINAL ###
            if (file_exists(storage_path('app/public/slider_images/' . $slide['image']))) {
                unlink(storage_path('app/public/slider_images/' . $slide['image']));
            }
            ### REMOVE RESIZED ###
            if (file_exists(storage_path('app/public/slider_images/150x150/' . $slide['image']))) {
                unlink(storage_path('app/public/slider_images/150x150/' . $slide['image']));
            }

            $data = $slide->delete();

            if ($data) {
                $res['status'] = true;
                $res['data'] = 'Successfully deleted the slide.';
            }
        }

        return json_encode($res);
    }
}
