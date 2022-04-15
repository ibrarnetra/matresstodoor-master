<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Page;

class PageDescription extends Model
{
    use HasFactory;

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    function getDescriptionsWithLanguages($page_id)
    {
        $query = DB::table('page_descriptions as cd')
            ->join('languages as l', 'l.id', '=', 'cd.language_id')
            ->select([
                'cd.title as title',
                'cd.content as content',
                'cd.meta_title as meta_title',
                'cd.meta_description as meta_description',
                'cd.meta_keyword as meta_keyword',
                'l.code as code',
            ])->where('cd.page_id', $page_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'title' => $val->title,
                'content' => $val->content,
                'meta_title' => $val->meta_title,
                'meta_description' => $val->meta_description,
                'meta_keyword' => $val->meta_keyword,
            ];
        }
        return $arr;
    }
}
