<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LengthClassDescription extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'length_class_descriptions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function length_class()
    {
        return $this->belongsTo(LengthClass::class);
    }

    function getDescriptionsWithLanguages($length_class_id)
    {
        $query = DB::table('length_class_descriptions as lcd')
            ->join('languages as l', 'l.id', '=', 'lcd.language_id')
            ->select([
                'lcd.title as title',
                'lcd.unit as unit',
                'l.code as code',
            ])->where('lcd.length_class_id', $length_class_id)->get()->toArray();

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
