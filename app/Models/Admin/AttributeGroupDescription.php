<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeGroupDescription extends Model
{
    use HasFactory;

    public function attribute_group()
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    function getDescriptionsWithLanguages($attribute_group_id)
    {
        $query = DB::table('attribute_group_descriptions as agd')
            ->join('languages as l', 'l.id', '=', 'agd.language_id')
            ->select([
                'agd.name as name',
                'l.code as code',
            ])->where('agd.attribute_group_id', $attribute_group_id)->get()->toArray();

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
