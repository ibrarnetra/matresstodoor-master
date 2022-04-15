<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Store;

class AttributeDescription extends Model
{
    use HasFactory;

    public function attribute()
    {
        return $this->belongsTo(Store::class);
    }

    function getDescriptionsWithLanguages($attribute_id)
    {
        $query = DB::table('attribute_descriptions as ad')
            ->join('languages as l', 'l.id', '=', 'ad.language_id')
            ->select([
                'ad.name as name',
                'l.code as code',
            ])->where('ad.attribute_id', $attribute_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'name' => $val->name,
            ];
        }
        return $arr;
    }
}
