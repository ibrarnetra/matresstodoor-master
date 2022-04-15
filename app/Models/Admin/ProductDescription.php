<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Product;
use App\Models\Admin\Language;

class ProductDescription extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    function _insert($product_descriptions, $product_id, $product_type)
    {
        if ($product_type == "admin") {
            ### FETCHING LANGUAGE ID BY CODE ###
            $language = (new Language())->getLangByCode('en');
            ### ADD DATA  TO PRODUCT DESCRIPTION ###
            $insert = [
                'product_id' => $product_id,
                'language_id' => $language->id,
                'name' => capAll($product_descriptions),
                "description" => $product_descriptions,
                "short_description" => $product_descriptions,
                "meta_title" => $product_descriptions,
                "meta_description" => $product_descriptions,
                "meta_keyword" => $product_descriptions,
            ];

            $id = self::create($insert);

            ### GENERATING SLUG ###
            Product::where('id', $product_id)->update([
                'slug' => Str::slug($product_descriptions) . "-" . $product_id,
            ]);
        } else {
            foreach ($product_descriptions as $key => $val) {
                ### FETCHING LANGUAGE ID BY CODE ###
                $language = (new Language())->getLangByCode($key);
                ### ADD DATA  TO PRODUCT DESCRIPTION ###
                $insert = [
                    'product_id' => $product_id,
                    'language_id' => $language->id,
                    'name' => capAll($val['name']),
                    "description" => (isset($val['description']) && !is_null($val['description'])) ? $val['description'] : "",
                    "short_description" => (isset($val['short_description']) && !is_null($val['short_description'])) ? $val['short_description'] : "",
                    "meta_title" => (isset($val['meta_title']) && !is_null($val['meta_title'])) ? $val['meta_title'] : "",
                    "meta_description" => (isset($val['meta_description']) && !is_null($val['meta_description'])) ? $val['meta_description'] : "",
                    "meta_keyword" => (isset($val['meta_keyword']) && !is_null($val['meta_keyword'])) ? $val['meta_keyword'] : "",
                ];
                $id = self::create($insert);
                ### GENERATING SLUG ###
                if ($key == 'en') {
                    Product::where('id', $product_id)->update([
                        'slug' => Str::slug($val['name']) . "-" . $product_id,
                    ]);
                }
            }
        }
    }

    function getDescriptionsWithLanguages($product_id)
    {
        $query = DB::table('product_descriptions as p')
            ->join('languages as l', 'l.id', '=', 'p.language_id')
            ->select([
                'p.name as name',
                'p.description as description',
                'p.short_description as short_description',
                'p.meta_title as meta_title',
                'p.meta_description as meta_description',
                'p.meta_keyword as meta_keyword',
                'l.code as code',
            ])->where('p.product_id', $product_id)->get()->toArray();

        $arr = [];
        foreach ($query as $val) {
            $code = $val->code;
            $arr[$code] = [
                'name' => $val->name,
                'description' => $val->description,
                'short_description' => $val->short_description,
                'meta_title' => $val->meta_title,
                'meta_description' => $val->meta_description,
                'meta_keyword' => $val->meta_keyword,
            ];
        }
        return $arr;
    }
}
