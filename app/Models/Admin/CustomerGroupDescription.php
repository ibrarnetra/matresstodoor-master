<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\OptionValueDescription;
use App\Models\Admin\CustomerGroup;

class CustomerGroupDescription extends Model
{
    use HasFactory;

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    function getDescriptionsWithLanguages($customer_group_id)
    {
        $query = DB::table('customer_group_descriptions as cgd')
            ->join('languages as l', 'l.id', '=', 'cgd.language_id')
            ->select([
                'cgd.name as name',
                'cgd.description as description',
                'l.code as code',
            ])->where('cgd.customer_group_id', $customer_group_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'name' => $val->name,
                'description' => $val->description,
            ];
        }
        return $arr;
    }
}
