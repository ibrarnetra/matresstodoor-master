<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\OptionValueDescription;

class OptionDescription extends Model
{
    use HasFactory;

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function option_values()
    {
        return $this->hasMany(OptionValue::class, 'option_id', 'option_id');
    }

    function getDescriptionsWithLanguages($option_id)
    {
        $query = DB::table('option_descriptions as od')
            ->join('languages as l', 'l.id', '=', 'od.language_id')
            ->select([
                'od.name as name',
                'l.code as code',
            ])->where('od.option_id', $option_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'name' => $val->name,
                'option_value_name' => (new OptionValueDescription())->getDescriptionsWithLanguages($option_id, $code),
            ];
        }
        return $arr;
    }
}
