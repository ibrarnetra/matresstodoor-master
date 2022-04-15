<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(OptionValueDescription::class, 'option_value_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(OptionValueDescription::class, 'option_value_id', 'id')->where('language_id', '=', '1');
    }

    function _getOptionValues($option_id)
    {
        return self::select('id', 'image', 'sort_order')
            ->where('option_id', $option_id)->get();
    }

    function _deleteOptionValue($id)
    {
        $res = ['status' => false, 'data' => 'Unable to process your request.'];
        $data = self::where('id', $id)->delete();
        $data = OptionValueDescription::where('option_value_id', $id)->delete();

        if ($data) {
            $res['status'] = true;
            $res['data'] = 'Success';
        }

        return json_encode($res);
    }
}
