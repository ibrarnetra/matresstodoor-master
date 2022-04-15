<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    function _show()
    {
        $settings = Setting::all();
        $arr = [];
        foreach ($settings as $key => $value) {
            $arr[$value->key] = $value->value;
        }
        return $arr;
    }

    function _update($request)
    {
        foreach ($request->data as $key => $value) {
            if ($key == "config_image" || $key == "config_logo" || $key == "config_favicon") {
                continue;
            }
            $setting = new Setting();
            $data = $setting->where('code', 'config')->where('key', $key)->first();
            if ($data) {
                self::where('code', 'config')->where('key', $key)->update(['value' => (isset($value) && !is_null($value)) ? $value : ""]);
            } else {
                $setting->code = "config";
                $setting->key = $key;
                $setting->value = (isset($value) && !is_null($value)) ? $value : "";
                $setting->save();
            }
        }

        if (isset($request->data['config_logo'])) {
            if ($request->hasFile('old_config_logo')) {
                ### REMOVE ORIGINAL ###
                if (file_exists(storage_path('app/public/config_logos/' . $request->old_image))) {
                    unlink(storage_path('app/public/config_logos/' . $request->old_image));
                }
                ### REMOVE RESIZED ###
                if (file_exists(storage_path('app/public/config_logos/150x150/' . $request->old_image))) {
                    unlink(storage_path('app/public/config_logos/150x150/' . $request->old_image));
                }
            }

            $file = saveImage($request->data['config_logo'], 'config_logos');

            $setting = new Setting();
            $data = $setting->where('code', 'config')->where('key', $key)->first();
            if ($data) {
                self::where('code', 'config')->where('key', $key)->update(['value' => $file]);
            } else {
                $setting->code = "config";
                $setting->key = "config_logo";
                $setting->value = $file;
                $setting->save();
            }
        }

        if (isset($request->data['config_favicon'])) {
            if ($request->hasFile('old_config_favicon')) {
                ### REMOVE ORIGINAL ###
                if (file_exists(storage_path('app/public/config_favicons/' . $request->old_image))) {
                    unlink(storage_path('app/public/config_favicons/' . $request->old_image));
                }
                ### REMOVE RESIZED ###
                if (file_exists(storage_path('app/public/config_favicons/150x150/' . $request->old_image))) {
                    unlink(storage_path('app/public/config_favicons/150x150/' . $request->old_image));
                }
            }

            $file = saveImage($request->data['config_favicon'], 'config_favicons');

            $setting = new Setting();
            $data = $setting->where('code', 'config')->where('key', $key)->first();
            if ($data) {
                self::where('code', 'config')->where('key', $key)->update(['value' => $file]);
            } else {
                $setting->code = "config";
                $setting->key = "config_favicon";
                $setting->value = $file;
                $setting->save();
            }
        }

        return true;
    }

    function _getStoreSetting($key)
    {
        $res = self::where('key', $key)->first();
        return ($res) ? $res->value : "N/A";
    }
}
