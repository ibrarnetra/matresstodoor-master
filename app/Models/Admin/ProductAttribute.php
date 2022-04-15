<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Language;

class ProductAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    function _insert($attributes, $product_id)
    {
        foreach ($attributes as $key => $val) {
            $language = (new Language())->getLangByCode($key);
            foreach ($val as $attribute) {
                if (!isset($attribute['attribute_id']) || is_null($attribute['attribute_id']) || !isset($attribute['attribute_text']) || is_null($attribute['attribute_text'])) {
                    continue;
                }
                $insert = [
                    'product_id' => $product_id,
                    'language_id' => $language->id,
                    'attribute_id' => $attribute['attribute_id'],
                    'text' => $attribute['attribute_text']
                ];
                $id = self::create($insert);
            }
        }
    }

    function getDescriptionsWithLanguages($product_id)
    {
        $query = DB::table('product_attributes as pa')
            ->join('languages as l', 'l.id', '=', 'pa.language_id')
            ->select([
                'pa.attribute_id as attribute_id',
                'pa.text as text',
                'l.code as code',
            ])->where('pa.product_id', $product_id)->get()->toArray();

        $arr = [];
        $eng_attribute_id = [];
        $eng_attribute_text = [];
        $ara_attribute_id = [];
        $ara_attribute_text = [];
        foreach ($query as $val) {
            $code = $val->code;
            if ($code == "en") {
                array_push($eng_attribute_id, $val->attribute_id);
                array_push($eng_attribute_text, $val->text);

                $arr[$code] = [
                    'attribute_id' => $eng_attribute_id,
                    'attribute_text' => $eng_attribute_text,
                ];
            }

            if ($code == "fr") {
                array_push($ara_attribute_id, $val->attribute_id);
                array_push($ara_attribute_text, $val->text);

                $arr[$code] = [
                    'attribute_id' => $ara_attribute_id,
                    'attribute_text' => $ara_attribute_text,
                ];
            }
        }
        return $arr;
    }

    function getProductAttributes($product_id)
    {
        $product_attributes = self::select('product_id', 'attribute_id', 'text')->with([
            'attribute_description' => function ($q) {
                $q->select('attribute_id', 'name')->where('language_id', $this->language_id);
            }
        ])->where('language_id', $this->language_id)->where('product_id', $product_id)->get();

        $arr = [];
        if (count($product_attributes) > 0) {
            foreach ($product_attributes as $product_attribute) {
                $temp = [
                    'key' => $product_attribute->attribute_description->name,
                    'value' => $product_attribute->text,
                ];
                $arr[] = $temp;
            }
        }
        return $arr;
    }
}
