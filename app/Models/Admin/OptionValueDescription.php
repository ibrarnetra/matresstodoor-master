<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\OptionValue;

class OptionValueDescription extends Model
{
    use HasFactory;


    public function option_value()
    {
        return $this->belongsTo(OptionValue::class);
    }

    function getDescriptionsWithLanguages($option_id, $code)
    {
        return DB::table('option_values as ov')
            ->join('option_value_descriptions as ovd', 'ovd.option_value_id', '=', 'ov.id')
            ->join('languages as l', 'l.id', '=', 'ovd.language_id')
            ->select([
                'ov.id as option_value_id',
                'ov.image as image',
                'ov.sort_order as sort_order',
                'ovd.name as name',
            ])->where('ovd.option_id', $option_id)->where('l.code', $code)->get()->toArray();
    }
}
