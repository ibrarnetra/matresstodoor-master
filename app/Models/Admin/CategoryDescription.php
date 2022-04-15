<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryDescription extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    function getDescriptionsWithLanguages($category_id)
    {
        $query = DB::table('category_descriptions as cd')
            ->join('languages as l', 'l.id', '=', 'cd.language_id')
            ->select([
                'cd.name as name',
                'cd.description as description',
                'cd.meta_title as meta_title',
                'cd.meta_description as meta_description',
                'cd.meta_keyword as meta_keyword',
                'l.code as code',
            ])->where('cd.category_id', $category_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'name' => $val->name,
                'description' => $val->description,
                'meta_title' => $val->meta_title,
                'meta_description' => $val->meta_description,
                'meta_keyword' => $val->meta_keyword,
            ];
        }
        return $arr;
    }
}
