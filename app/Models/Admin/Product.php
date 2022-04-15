<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use Carbon\Carbon;
use App\Models\Admin\Store;
use App\Models\Admin\StockStatus;
use App\Models\Admin\Review;
use App\Models\Admin\ProductSpecial;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\ProductOption;
use App\Models\Admin\ProductImage;
use App\Models\Admin\ProductDescription;
use App\Models\Admin\ProductAttribute;
use App\Models\Admin\OrderProduct;
use App\Models\Admin\OrderOption;
use App\Models\Admin\OptionValueDescription;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\Language;
use App\Models\Admin\Customer;
use App\Models\Admin\Category;

class Product extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class, 'product_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'id')->where('language_id', '1');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }

    public function related()
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function specials()
    {
        return $this->hasMany(ProductSpecial::class);
    }

    public function product_option_values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function order_options()
    {
        return $this->hasMany(OrderOption::class, 'order_product_id', 'id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_wishlist');
    }

    public function stock_status()
    {
        return $this->belongsTo(StockStatus::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approved_reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', getConstant('APPROVED'));
    }

    public function original_images()
    {
        return $this->hasMany(ProductImage::class)->where('type', 'original');
    }

    public function thumbnail_image()
    {
        return $this->hasOne(ProductImage::class)->where('type', 'thumbnail');
    }

    public function discount()
    {
        return $this->hasOne(ProductSpecial::class)
            ->where(DB::raw('DATE(date_start)'), '<=', Carbon::now())
            ->where(DB::raw('DATE(date_end)'), '>=', Carbon::now())
            ->orderBy('priority', 'ASC')
            ->orderBy('price', 'ASC');
    }

    public function product_options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    function _store($request)
    {
        $is_clone = (isset($request->is_clone) && !is_null($request->is_clone)) ? true : false;

        $product = new Product();

        ### DIMENSIONS AND WEIGHT ###
        $product->manufacturer_id = (isset($request->manufacturer_id) && !is_null($request->manufacturer_id)) ? $request->manufacturer_id : 0;
        $product->tax_class_id = (isset($request->tax_class_id) && !is_null($request->tax_class_id)) ? $request->tax_class_id : 0;
        $product->length_class_id = (isset($request->length_class_id) && !is_null($request->length_class_id)) ? $request->length_class_id : 0;
        $product->weight_class_id = (isset($request->weight_class_id) && !is_null($request->weight_class_id)) ? $request->weight_class_id : 0;
        $product->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : 1;
        $product->length = (isset($request->length) && !is_null($request->length)) ? $request->length : 0;
        $product->width = (isset($request->width) && !is_null($request->width)) ? $request->width : 0;
        $product->height = (isset($request->height) && !is_null($request->height)) ? $request->height : 0;
        $product->weight = (isset($request->weight) && !is_null($request->weight)) ? $request->weight : 0;

        $product->sku = (isset($request->sku) && !is_null($request->sku)) ? $request->sku : null;
        $product->model = (isset($request->model) && !is_null($request->model)) ? $request->model : null;
        $product->price = $request->price;
        $product->quantity = (isset($request->quantity) && !is_null($request->quantity)) ? $request->quantity : "0";
        $product->minimum = (isset($request->minimum) && !is_null($request->minimum)) ? $request->minimum : 1;
        $product->subtract = (isset($request->subtract) && !is_null($request->subtract)) ? $request->subtract : 0;
        $product->stock_status_id = (isset($request->stock_status_id) && !is_null($request->stock_status_id)) ? $request->stock_status_id : 1;
        $product->shipping = (isset($request->shipping) && !is_null($request->shipping)) ? $request->shipping : "1";
        $product->date_available = (isset($request->date_available) && !is_null($request->date_available)) ? $request->date_available : getCurrentDate();
        $product->type = (isset($request->product_type) && !is_null($request->product_type)) ? $request->product_type : "all";

        $product->status = ($is_clone) ? getConstant('IS_NOT_STATUS_ACTIVE') : getConstant('IS_STATUS_ACTIVE');

        $product->save();


        $product_id = $product->id;

        ### PIVOT INSERTION FOR product_store ###
        if ($request->has('stores')) {
            $product->stores()->sync($request->stores);
        }
        ### PIVOT INSERTION FOR product_related ###
        if ($request->has('related')) {
            $product->related()->sync($request->related);
        }
        ### PIVOT INSERTION FOR category_product ###
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }
        if ($request->has('category_id')) {
            $product->categories()->sync([$request->category_id]);
        }
        ### INSERT PRODUCT ATTRIBUTES ###
        if ($request->has('attribute')) {
            (new ProductAttribute())->_insert($request->attribute, $product_id);
        }
        ### INSERT PRODUCT DESCRIPTION ###
        if ($request->has('product_description')) {
            (new ProductDescription())->_insert($request->product_description, $product_id, 'all');
        } else {
            (new ProductDescription())->_insert($request->name, $product_id, 'admin');
        }

        /**
         * Handle original image
         */
        if ($request->hasFile('image')) {
            (new ProductImage())->_insert($request->image, $product_id);
        }

        /**
         * Handle thumbnail image
         */
        if ($request->hasFile('thumbnail')) {
            $extension = $request->thumbnail->getClientOriginalExtension(); // get extension
            $file_name_to_store = getCurrentDate() . '_' . uniqid() . '.' . $extension; // renaming image
            Storage::put('public/product_images/thumbnail/' . $file_name_to_store, fopen($request->thumbnail, 'r+')); // storing file

            /**
             * saving renamed image into database
             */
            ProductImage::create([
                'product_id' => $product_id,
                'image' => $file_name_to_store,
                'type' => 'thumbnail',
                'sort_order' => '0',
            ]);
        }
        ### INSERT PRODUCT SPECIALS ###
        if ($request->has('special')) {
            (new ProductSpecial())->_insert($request->special, $product_id);
        }
        ### INSERT PRODUCT OPTIONS ###
        if ($request->has('option')) {
            (new ProductOption())->_insert($request->option, $product_id, 'add');
        }

        return $product_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            ### DIMENSIONS AND WEIGHT ###
            "manufacturer_id" => (isset($request->manufacturer_id) && !is_null($request->manufacturer_id)) ? $request->manufacturer_id : 0,
            "tax_class_id" => (isset($request->tax_class_id) && !is_null($request->tax_class_id)) ? $request->tax_class_id : 0,
            "length_class_id" => (isset($request->length_class_id) && !is_null($request->length_class_id)) ? $request->length_class_id : 0,
            "weight_class_id" => (isset($request->weight_class_id) && !is_null($request->weight_class_id)) ? $request->weight_class_id : 0,
            "sort_order" => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : 1,
            "length" => (isset($request->length) && !is_null($request->length)) ? $request->length : 0,
            "width" => (isset($request->width) && !is_null($request->width)) ? $request->width : 0,
            "height" => (isset($request->height) && !is_null($request->height)) ? $request->height : 0,
            "weight" => (isset($request->weight) && !is_null($request->weight)) ? $request->weight : 0,

            "sku" => (isset($request->sku) && !is_null($request->sku)) ? $request->sku : null,
            "model" => (isset($request->model) && !is_null($request->model)) ? $request->model : null,
            "price" => $request->price,
            "quantity" => $request->quantity,
            "minimum" => $request->minimum,
            "subtract" => $request->subtract,
            "stock_status_id" => $request->stock_status_id,
            "shipping" => (isset($request->shipping) && !is_null($request->shipping)) ? $request->shipping : "1",
            "date_available" => $request->date_available,
            "type" => (isset($request->product_type) && !is_null($request->product_type)) ? $request->product_type : "all",
        ]);

        foreach ($request->product_description as $key => $val) {
            ### FETCHING LANGUAGE ID BY CODE ###
            $language = (new Language())->getLangByCode($key);
            ### ADD DATA  TO PRODUCT DESCRIPTION ###
            ProductDescription::where(['product_id' => $id, 'language_id' => $language->id])->update([
                "name" => capAll($val['name']),
                "description" => (isset($val['description']) && !is_null($val['description'])) ? $val['description'] : "",
                "meta_title" => (isset($val['meta_title']) && !is_null($val['meta_title'])) ? $val['meta_title'] : "",
                "meta_description" => (isset($val['meta_description']) && !is_null($val['meta_description'])) ? $val['meta_description'] : "",
                "meta_keyword" => (isset($val['meta_keyword']) && !is_null($val['meta_keyword'])) ? $val['meta_keyword'] : "",
            ]);
        }

        $product = Product::where('id', $id)->first();
        ### PIVOT INSERTION FOR product_store ###
        if ($request->has('stores')) {
            $product->stores()->sync($request->stores);
        } else {
            $product->stores()->sync([]);
        }
        ### PIVOT INSERTION FOR product_related ###
        if ($request->has('related')) {
            $product->related()->sync($request->related);
        } else {
            $product->related()->sync([]);
        }
        ### PIVOT INSERTION FOR category_product ###
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->sync([]);
        }

        ### INSERT PRODUCT ATTRIBUTES ###
        if ($request->has('attribute')) {
            ### DELETE EXISTING PRODUCT ATTRIBUTES ###
            $attribute_ids = [];
            foreach ($request->attribute['en'] as $attribute) {
                if (!isset($attribute['attribute_id']) || is_null($attribute['attribute_id']) || !isset($attribute['attribute_text']) || is_null($attribute['attribute_text'])) {
                    continue;
                }
                array_push($attribute_ids, $attribute['attribute_id']);
            }
            ProductAttribute::where('product_id', $id)->whereIn('attribute_id', $attribute_ids)->delete();
            (new ProductAttribute())->_insert($request->attribute, $id);
        }
        ### INSERT PRODUCT SPECIAL ###
        if ($request->has('special')) {
            ### DELETE EXISTING PRODUCT SPECIALS ###
            ProductSpecial::where('product_id', $id)->delete();
            (new ProductSpecial())->_insert($request->special, $id);
        }
        ### INSERT PRODUCT OPTIONS ###
        if ($request->has('option')) {

            (new ProductOption())->_insert($request->option, $id, 'edit');
        }

        /**
         * Handle original image
         */
        if ($request->hasFile('image')) {
            (new ProductImage())->_insert($request->image, $id);
        }

        /**
         * Handle thumbnail image
         */
        if ($request->hasFile('thumbnail')) {
            $extension = $request->thumbnail->getClientOriginalExtension(); // get extension
            $file_name_to_store = getCurrentDate() . '_' . uniqid() . '.' . $extension; // renaming image
            Storage::put('public/product_images/thumbnail/' . $file_name_to_store, fopen($request->thumbnail, 'r+')); // storing file

            /**
             * saving renamed image into database
             */
            ProductImage::create([
                'product_id' => $id,
                'image' => $file_name_to_store,
                'type' => 'thumbnail',
                'sort_order' => '0',
            ]);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::with([
            'specials' => function ($q) {
                $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end')->orderBy('priority', 'ASC')->orderBy('price', 'ASC');
            },
            'original_images' => function ($q) {
                $q->select('id', 'product_id', 'image');
            },
            'thumbnail_image' => function ($q) {
                $q->select('id', 'product_id', 'image');
            }
        ])->where('id', $id)->first();

        return [
            "id" => $query->id,
            "stock_status_id" => $query->stock_status_id,
            "manufacturer_id" => $query->manufacturer_id,
            "weight_class_id" => $query->weight_class_id,
            "length_class_id" => $query->length_class_id,
            "tax_class_id" => $query->tax_class_id,
            "quantity" => $query->quantity,
            "cuisine_id" => $query->cuisine_id,
            "shipping" => $query->shipping,
            "model" => $query->model,
            "sku" => $query->sku,
            "price" => $query->price,
            "date_available" => $query->date_available,
            "weight" => $query->weight,
            "length" => $query->length,
            "width" => $query->width,
            "height" => $query->height,
            "subtract" => $query->subtract,
            "minimum" => $query->minimum,
            "sort_order" => $query->sort_order,
            "is_featured" => $query->is_featured,
            "is_deal" => $query->is_deal,
            "deal_image" => $query->deal_image,
            "status" => $query->status,
            "viewed" => $query->viewed,
            "original_images" => $query->original_images,
            "thumbnail_image" => $query->thumbnail_image,
            "product_description" => (new ProductDescription())->getDescriptionsWithLanguages($id),
            "attribute" => (new ProductAttribute())->getDescriptionsWithLanguages($id),
            "stores" => (new Store())->pluckIds($query->id, 'product_store', 'product_id'),
            "categories" => (new Category())->pluckIds($query->id, 'category_product', 'product_id'),
            "related" => (new Product())->getRelatedProducts($query->id),
            "specials" => $query->specials,
            "options" => (new ProductOption())->getOptions($query->id),
        ];
    }

    function getRelatedProducts($id)
    {
        return DB::table('product_related as pr')
            ->join('products as p', 'p.id', '=', 'pr.related_id')
            ->join('product_descriptions as pd', 'pd.product_id', '=', 'p.id')
            ->join('languages as l', 'l.id', '=', 'pd.language_id')
            ->select('p.id as id', 'pd.name as name')
            ->where('l.id', '=', '1')
            ->where('pr.product_id', $id)->get()->toArray();
    }

    function _search($request)
    {
        $search_q = "%" . $request->q . "%";
        $products = self::join('product_descriptions', function ($q) use ($search_q) {
            $q->on('product_descriptions.product_id', '=', 'products.id')
                ->where('product_descriptions.language_id', '=', '1')
                ->where('product_descriptions.name', 'like', $search_q);
        })
            ->where('products.is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('products.status', getConstant('IS_STATUS_ACTIVE'))
            ->select(
                'products.id as product_id',
                'product_descriptions.name as name'
            )
            ->get();

        $arr = [];
        if (count($products) > 0) {
            foreach ($products as $product) {
                $temp['id'] = $product->product_id;
                $temp['text'] = $product->name;
                $arr[] = $temp;
            }
        }
        return json_encode(["status" => true, "search" => $arr, 'data' => $products]);
    }

    function _loadOptionValue($request)
    {
        $data_id = $request->input('option-id');
        $option_arr = explode("-", $request->input('option-id'));
        $option_id = $option_arr[1];
        $temp_key = rand(10, 1000);
        $option_values = OptionValueDescription::where('option_id', $option_id)->where('language_id', '1')->get();
        $view = view('admin.products.option_value', compact('option_values', 'data_id', 'option_id', 'temp_key'))->render();
        $response["data"] = $view;
        return json_encode($response);
    }

    function _deleteOptionValue($request)
    {
        $option_id = $request->input('option-id');
        ProductOption::where('id', $option_id)->delete();
        ProductOptionValue::where('product_option_id', $option_id)->delete();
        $data = true;
        if ($data) {
            $result = ['status' => true, 'message' => 'success'];
        }
        return json_encode($result);
    }

    function _getOptions($request)
    {
        $product_id = $request->input('id');
        $currency_symbol = $request->input('currency_symbol');
        $options = (new ProductOption())->getOptions($product_id);

        $arr = [];
        foreach ($options as $option) {
            array_push($arr, $option->option->type);
        }

        $options_html = view('admin.products.options', compact('options', 'currency_symbol'))->render();
        return json_encode(['status' => true, 'data' => $options_html, 'option_type_order' => $arr]);
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            $products = self::with([
                'discount' => function ($q) {
                    $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
                },
                'thumbnail_image' => function ($q) {
                    $q->select('id', 'product_id', 'image');
                },
                'eng_description' => function ($q) {
                    $q->select('product_id', 'language_id', 'name', 'description', 'short_description');
                },
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('image', function ($row) {
                    $image = '';
                    if ($row->thumbnail_image) {
                        $image = $row->thumbnail_image->image;
                    }
                    return $image;
                })
                ->addColumn('total_quantity', function ($row) {
                    $quantity =  $row->quantity;
                    if (isset($row->product_option_values)) {
                        foreach ($row->product_option_values as $option_value) {
                            $quantity += $option_value->quantity;
                        }
                    }
                    return $quantity;
                })
                ->addColumn('price', function ($row) {
                    return [
                        'price' => intval($row->price),
                        'discount' => (isset($row->discount) && !is_null($row->discount)) ? intval($row->discount->price) : 0,
                    ];
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('products.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '<div class="dropdown">
                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" type="button" id="' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="' . $row->id . '">';

                    $action .= '<a href="' . route('products.edit', ['id' => $row->id, 'type' => 'create']) . '" class="dropdown-item">
                                        Copy
                                    </a>';

                    $slug_params = "'" . route('products.editSlug', ['id' => $row->id]) . "'";
                    $action .= '<a href="javascript:void(0);" class="dropdown-item" onclick="toggleSlugModal(' . $slug_params . ')">
                                        Edit Slug
                                    </a>';

                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Products') && $row->type == "all") {
                        $action .= '<a href="' . route('products.edit', ['id' => $row->id]) . '" class="dropdown-item">
                                        Edit
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Read-Product-Purchases')) {
                        $action .= '<a href="' . route('products.purchaseHistory', ['id' => $row->id]) . '" class="dropdown-item">
                                        Purchase History
                                    </a>';
                    }


                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Products')) {
                        $param = "'" . route('products.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="dropdown-item" onclick="deleteData(' . $param . ')">
                                        Delete
                                    </a>';
                    }

                    $action .= '</ul></div>';
                    return $action;
                })
                ->rawColumns(['name', 'image', 'total_quantity', 'status', 'action'])
                ->make(true);
        }
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _deleteImage($request)
    {
        if ($request->type == "original") {
            ### REMOVE ORIGINAL ###
            if (file_exists(storage_path('app/public/product_images/' . $request->image))) {
                unlink(storage_path('app/public/product_images/' . $request->image));
            }
            ### REMOVE RESIZED ###
            if (file_exists(storage_path('app/public/product_images/150x150/' . $request->image))) {
                unlink(storage_path('app/public/product_images/150x150/' . $request->image));
            }
        } else {
            ### REMOVE THUMBNAIL ###
            if (file_exists(storage_path('app/public/product_images/thumbnail/' . $request->image))) {
                unlink(storage_path('app/public/product_images/thumbnail/' . $request->image));
            }
        }

        $product_image = ProductImage::where('product_id', $request->id)
            ->where('type', $request->type)
            ->where('image', $request->image)
            ->delete();

        if ($product_image) {
            return json_encode(['status' => true, 'message' => 'success']);
        }
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }

    function getProduct($product_id)
    {
        return self::select('id', 'slug', 'price', 'minimum', 'subtract', 'quantity')->with([
            'discount' => function ($q) {
                $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
            },
            'eng_description' => function ($q) {
                $q->select('product_id', 'language_id', 'name');
            }
        ])
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('id', $product_id)
            ->first();
    }

    function _getProductWithSlug($slug)
    {
        return $query = self::select('id', 'slug', 'price', 'minimum', 'subtract')->withCount([
            'approved_reviews',
        ])
            ->with([
                'eng_description' => function ($q) {
                    $q->select('product_id', 'language_id', 'name', 'description', 'short_description', 'meta_title', 'meta_description', 'meta_keyword');
                },
                'discount' => function ($q) {
                    $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
                },
                'thumbnail_image' => function ($q) {
                    $q->select('id', 'product_id', 'image');
                },
                'original_images' => function ($q) {
                    $q->select('id', 'product_id', 'image');
                },
                'product_options' => function ($q) {
                    $q->select('id', 'product_id', 'option_id', 'value', 'required');
                },
                'product_options.option' => function ($q) {
                    $q->select('id', 'type');
                },
                'product_options.eng_description' => function ($q) {
                    $q->select('option_id', 'language_id', 'name');
                },
                'product_options.product_option_values' => function ($q) {
                    $q->select('id', 'product_option_id', 'product_id', 'option_id', 'option_value_id', 'quantity', 'subtract', 'price', 'price_prefix', 'weight', 'weight_prefix');
                },
                'product_options.product_option_values.eng_description' => function ($q) {
                    $q->select('option_value_id', 'language_id', 'name');
                },
                'approved_reviews' => function ($q) {
                    $q->select('id', 'product_id', 'name', 'rating', 'review', 'created_at');
                },
            ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('slug', $slug)
            ->first();

        // $sql = $query->toSql();
        // $bindings = $query->getBindings();
        // return [$sql, $bindings];
    }

    function _getAllProducts($request)
    {
        ### FILTER PARAMS ###
        $order_by_filter = (!is_null($request->input('order-by')) && $request->input('order-by') != "") ? $request->input('order-by') : "-1";
        $price_filter = (!is_null($request->input('price-filter')) && $request->input('price-filter') != "") ? explode('-', $request->input('price-filter')) : "-1";
        $category_filter = (!is_null($request->input('category')) && $request->input('category') != "") ? $request->input('category') : "-1";
        $manufacturer_filter = (!is_null($request->input('manufacturer')) && $request->input('manufacturer') != "") ? $request->input('manufacturer') : "-1";
        $q = (!is_null($request->input('q')) && $request->input('q') != "") ? $request->input('q') : "-1";
        $type = (!is_null($request->input('type')) && $request->input('type') != "") ? $request->input('type') : "-1";
        $variant = (!is_null($request->input('variant')) && $request->input('variant') != "") ? $request->input('variant') : "-1";

        ### BASE QUERY ###
        $query = Product::select(
            'products.id',
            'products.manufacturer_id',
            'products.slug',
            'products.price',
            'products.minimum',
            'products.subtract',
            'products.status',
            'products.is_deleted'
        )->withCount([
            'approved_reviews',
        ])
            ->with([
                'discount' => function ($q) {
                    $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
                },
                'thumbnail_image' => function ($q) {
                    $q->select('id', 'product_id', 'image');
                },
                'eng_description' => function ($q) {
                    $q->select('product_id', 'language_id', 'name', 'description', 'short_description');
                },
                'original_images' => function ($q) {
                    $q->select('id', 'product_id', 'image');
                },
                'categories' => function ($q) {
                    $q->select('id', 'slug')->with([
                        'eng_description' => function ($q) {
                            $q->select('category_id', 'language_id', 'name', 'description');
                        },
                    ]);
                },
                'approved_reviews' => function ($q) {
                    $q->select('id', 'product_id', 'name', 'rating', 'review', 'created_at');
                },
            ])
            ->join('product_descriptions', function ($q) {
                $q->on('product_descriptions.product_id', '=', 'products.id')->where('product_descriptions.language_id', '1');
            });

        ### VARIANT FILTER ###
        if ($variant != "-1") {
            $query->join('product_option_values', function ($q) {
                $q->on('product_option_values.product_id', '=', 'products.id');
            });
        }

        $query->leftJoin('category_product', function ($q) {
            $q->on('category_product.product_id', '=', 'products.id')
                ->leftJoin('categories', function ($q) {
                    $q->on('categories.id', '=', 'category_product.category_id');
                });
        })
            ->leftJoin('manufacturers', function ($q) {
                $q->on('manufacturers.id', '=', 'products.manufacturer_id');
            })
            ->where('products.type', getConstant('PRODUCTS_WITHOUT_ADMIN_LVL'))
            ->where('products.is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('products.status', getConstant('IS_STATUS_ACTIVE'));

        ### VARIANT FILTER ###
        if ($variant != "-1") {
            $query->where('product_option_values.option_value_id', '=', $variant);
        }

        ### MANUFACTURER FILTER ###
        if ($manufacturer_filter != "-1") {
            $query->where('manufacturers.slug', '=', $manufacturer_filter);
        }

        ### CATEGORY FILTER ###
        if ($category_filter != "-1") {
            $query->where('categories.slug', '=', $category_filter);
        }

        ### SEARCH ###
        if ($q != "-1") {
            $query->where('product_descriptions.name', 'like', '%' . $q . '%');
        }

        ### PRICE FILTER ###
        if ($price_filter != "-1") {
            $query->whereBetween('price', $price_filter);
        }

        ### DISCOUNT FILTER ###
        if ($type == "discounted") {
            $query->has('discount');
        }

        ### GROUP BY ###
        $query->groupBy('products.id');

        ### ORDER BY FILTER ###
        if ($order_by_filter == "date") {
            $query->orderBy('products.created_at', 'ASC');
        }
        if ($order_by_filter == "price-asc") {
            $query->orderBy('products.price', 'ASC');
        }
        if ($order_by_filter == "price-desc") {
            $query->orderBy('products.price', 'DESC');
        }
        if ($order_by_filter == "a-z") {
            $query->orderBy('product_descriptions.name', 'ASC');
        }
        if ($order_by_filter == "z-a") {
            $query->orderBy('product_descriptions.name', 'DESC');
        }

        return $query->paginate(9);
    }

    function _getMaxPrice()
    {
        return self::where('type', getConstant('PRODUCTS_WITHOUT_ADMIN_LVL'))
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->max('price');
    }

    function _getMinPrice()
    {
        return self::where('type', getConstant('PRODUCTS_WITHOUT_ADMIN_LVL'))
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->min('price');
    }

    function _addToWishlist($product_id, $user_id)
    {
        $item = DB::table('customer_wishlist')->where([
            'customer_id' => $user_id,
            'product_id' => $product_id,
        ])->first();

        if (!$item) {
            $item = DB::table('customer_wishlist')->insert([
                'customer_id' => $user_id,
                'product_id' => $product_id,
            ]);
        }
        return $item;
    }

    function _removeFromWishlist($product_id, $user_id)
    {
        return DB::table('customer_wishlist')->where([
            'customer_id' => $user_id,
            'product_id' => $product_id,
        ])->delete();
    }

    function _getWishlistProducts($user_id)
    {
        return Customer::select('id', 'first_name', 'last_name')->with([
            'products' => function ($q) {
                $q->select('id', 'stock_status_id', 'model', 'price', 'slug');
            },
            'products.discount' => function ($q) {
                $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
            },
            'products.eng_description' => function ($q) {
                $q->select('product_id', 'language_id', 'name');
            },
            'products.thumbnail_image' => function ($q) {
                $q->select('id', 'product_id', 'image');
            },
            'products.stock_status' => function ($q) {
                $q->select('id', 'name');
            }
        ])
            ->where('id', $user_id)
            ->first();
    }

    function _updateSlug($request, $id)
    {
        return self::where('id', $id)->update(['slug' => $request->slug . "-" . $id]);
    }

    function _decrementProduct($product_id , $quantity)
    {
        $product = Product::where('id', $product_id)->first();
        if ($product) {
            $updated_qty = $product->quantity -  $quantity;
            $product->quantity = $updated_qty;
            $product->save();
            ### UPDATE STOCK STATUS DEPENDING ON THE UPDATED QTY ###
            if ($updated_qty == 0 || $updated_qty < $product->minimum) {
                Product::where('id', $product->id)->update(['stock_status_id' => 2]);
            }
        }

    }

    function _incrementProduct($product_id , $quantity)
    {
        $product = Product::where('id', $product_id)->first();
        if ($product) {
            $updated_qty = $product->quantity +  $quantity;
            $product->quantity = $updated_qty;
            $product->save();
            ### UPDATE STOCK STATUS DEPENDING ON THE UPDATED QTY ###
            if ($updated_qty == 0 || $updated_qty < $product->minimum) {
                Product::where('id', $product->id)->update(['stock_status_id' => 2]);
            }
        }

    }

    function _decrementProductOption($product_option_value_id, $quantity)
    {
        $product_option_value = ProductOptionValue::where('id', $product_option_value_id)->first();
        if ($product_option_value && $product_option_value->subtract == '1') {
            $product_option_value->quantity =   $product_option_value->quantity -  $quantity;
            $product_option_value->save();
        }

    }

    //to sync all store to every product
    public function productSyncStore()
    {
        $products = Product::where('is_deleted','0')->get();
        $store_ids = Store::where('is_deleted','0')->select('id')->pluck('id')->toArray();
        foreach($products as $product)
        {
            $product->stores()->sync($store_ids);

        }
    }
}
