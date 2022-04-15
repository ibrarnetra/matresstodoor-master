<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\WeightClass;

class WeightClassDescription extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weight_class_descriptions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function weight_class()
    {
        return $this->belongsTo(WeightClass::class);
    }

    function getDescriptionsWithLanguages($weight_class_id)
    {
        $query = DB::table('weight_class_descriptions as lcd')
            ->join('languages as l', 'l.id', '=', 'lcd.language_id')
            ->select([
                'lcd.title as title',
                'lcd.unit as unit',
                'l.code as code',
            ])->where('lcd.weight_class_id', $weight_class_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'title' => $val->title,
                'unit' => $val->unit,
            ];
        }
        return $arr;
    }
}
