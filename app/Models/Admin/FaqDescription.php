<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaqDescription extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    function getDescriptionsWithLanguages($faq_id)
    {
        $query = DB::table('faq_descriptions as fd')
            ->join('languages as l', 'l.id', '=', 'fd.language_id')
            ->select([
                'fd.question as question',
                'fd.answer as answer',
                'l.code as code',
            ])->where('fd.faq_id', $faq_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'question' => $val->question,
                'answer' => $val->answer,
            ];
        }
        return $arr;
    }
}
