<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Store;
use App\Models\Admin\Product;
use App\Models\Admin\Language;
use App\Models\Admin\CategoryDescription;

class Category extends Model
{
    use HasFactory;

    public $language_id;
    public $code;

    public function __construct($code = 'en')
    {
        $this->language_id = ($code == "en") ? "1" : "2";
        $this->code = $code;
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class, 'category_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(CategoryDescription::class, 'category_id', 'id')->where('language_id', '1');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    function pluckIds($id, $table, $col)
    {
        return DB::table($table)->where($col, $id)->pluck('category_id')->toArray();
    }

    function _store($request)
    {
        $category = new Category();
        $category->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";
        $category->parent_id = (isset($request->parent_id) && !is_null($request->parent_id)) ? $request->parent_id : "0";

        if ($request->hasFile('image')) {
            $category->image = saveImage($request->image, 'category_images');
        }

        $category->save();

        $category_id = $category->id;

        foreach ($request->category_description as $key => $val) {
            $category_description = new CategoryDescription();

            $language = (new Language())->getLangByCode($key);

            $category_description->category_id = $category_id;
            $category_description->language_id = $language->id;

            $category_description->name = capAll($val['name']);
            $category_description->description = $val['description'];

            $category_description->meta_title = capAll($val['meta_title']);
            $category_description->meta_description = $val['meta_description'];
            $category_description->meta_keyword = $val['meta_keyword'];
            $category_description->save();
            ### GENERATING SLUG ###
            if ($key == 'en') {
                self::where('id', $category_id)->update([
                    'slug' => Str::slug($val['name']) . "-" . $category_id,
                ]);
            }
        }

        ### SYNCING FOR PIVOT ###
        $category->stores()->sync($request->stores);

        return $category_id;
    }

    function _update($request, $id)
    {
        if ($request->hasFile('image')) {
            if ($request->hasFile('old_image')) {
                ### REMOVE ORIGINAL ###
                if (file_exists(storage_path('app/public/category_images/' . $request->old_image))) {
                    unlink(storage_path('app/public/category_images/' . $request->old_image));
                }
                ### REMOVE RESIZED ###
                if (file_exists(storage_path('app/public/category_images/150x150/' . $request->old_image))) {
                    unlink(storage_path('app/public/category_images/150x150/' . $request->old_image));
                }
            }

            $image = saveImage($request->image, 'category_images');
            self::where('id', $id)->update([
                "image" => $image,
            ]);
        }

        self::where('id', $id)->update([
            "parent_id" => isset($request->parent_id) && !is_null($request->parent_id) ? $request->parent_id : "0",
            "sort_order" => isset($request->sort_order) && !is_null($request->sort_order) ? $request->sort_order : "1",
        ]);

        foreach ($request->category_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            CategoryDescription::where(['category_id' => $id, 'language_id' => $language->id])->update([
                "name" => capAll($val['name']),
                "description" => $val['description'],
            ]);
        }

        ### SYNCING FOR PIVOT ###
        $category = Category::where('id', $id)->first();
        if ($request->has('stores')) {
            $category->stores()->sync($request->stores);
        } else {
            $category->stores()->sync([]);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->orWhere('parent_id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::select('id', 'parent_id', 'image', 'status', 'sort_order', 'is_deleted')
            ->where('id', $id)
            ->first();

        return array(
            "id" => $query->id,
            "stores" => (new Store())->pluckIds($query->id, 'category_store', 'category_id'),
            "parent_id" => $query->parent_id,
            "image" => $query->image,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
            "parent_category" => [
                "category_description" => (new CategoryDescription())->getDescriptionsWithLanguages($query->parent_id),
            ],
            "category_description" => (new CategoryDescription())->getDescriptionsWithLanguages($id),
        );
    }

    function _dataTable($request)
    {

        if ($request->ajax()) {
            $categories = self::select('id', 'parent_id', 'image', 'sort_order', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('category_id', 'language_id', 'name', 'description');
                },
                'parent.eng_description',
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($categories)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = $row->eng_description->name;
                    if ($row->parent) {
                        $name = $row->parent->eng_description->name . " > " . $row->eng_description->name;
                    }
                    return $name;
                })
                ->addColumn('description', function ($row) {
                    return substr(strip_tags($row->eng_description->description), 0, 100);
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('categories.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
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

                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Categories')) {
                        $action .= '<a href="' . route('categories.edit', ['id' => $row->id]) . '" class="dropdown-item">
                                        Edit
                                    </a>';
                    }

                    $slug_params = "'" . route('categories.editSlug', ['id' => $row->id]) . "'";
                    $action .= '<a href="javascript:void(0);" class="dropdown-item" onclick="toggleSlugModal(' . $slug_params . ')">
                                        Edit Slug
                                    </a>';

                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Categories')) {
                        $param = "'" . route('categories.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" onclick="deleteData(' . $param . ')" class="dropdown-item">
                                        Delete
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns([
                    'name', 'description', 'status', 'action'
                ])
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

    function _search($request)
    {
        $q = "%" . $request->q . "%";
        $categories = CategoryDescription::where('name', 'like', $q)->where('language_id', '1')->get();
        $arr = [];
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $temp['id'] = $category->category_id;
                $temp['text'] = $category->name;
                $arr[] = $temp;
            }
        }
        return json_encode(["status" => true, "search" => $arr, 'data' => $categories]);
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

    function _getLimitedCategoriesForFrontend()
    {
        return Category::select('id', 'parent_id', 'slug', 'image', 'status', 'is_deleted')->with([
            'eng_description' => function ($q) {
                $q->select('category_id', 'language_id', 'name');
            },
            'children' => function ($q) {
                $q->with([
                    'eng_description' => function ($q) {
                        $q->select('category_id', 'language_id', 'name');
                    },
                ])
                    ->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->where('parent_id', 0)
            ->orderBy('sort_order', 'ASC')
            ->limit('6')
            ->get();
    }

    function _getLimitedCategoriesWithProductsForFrontend()
    {
        return Category::select('id', 'parent_id', 'slug', 'image', 'status', 'is_deleted')->withCount([
            'products' => function ($q) {
                $q->where('type', getConstant('PRODUCTS_WITHOUT_ADMIN_LVL'))
                    ->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            }
        ])->with([
            'eng_description' => function ($q) {
                $q->select('category_id', 'language_id', 'name');
            },
            'products' =>  function ($q) {
                $q->withCount([
                    'approved_reviews',
                ])
                    ->with([
                        'manufacturer' => function ($q) {
                            $q->select('id', 'slug', 'name', 'image');
                        },
                        'discount' => function ($q) {
                            $q->select('id', 'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
                        },
                        'eng_description' => function ($q) {
                            $q->select('product_id', 'language_id', 'name', 'description', 'short_description');
                        },
                        'thumbnail_image' => function ($q) {
                            $q->select('id', 'product_id', 'image');
                        },
                        'approved_reviews' => function ($q) {
                            $q->select('id', 'product_id', 'name', 'rating', 'review', 'created_at');
                        },
                        'product_options' => function ($q) {
                            $q->select('id', 'product_id', 'value')->with([
                                'product_option_values' =>  function ($q) {
                                    $q->select('id', 'product_option_id', 'option_id', 'option_value_id', 'price', 'price_prefix')->with([
                                        'option' => function ($q) {
                                            $q->select('id', 'type', 'sort_order')->with([
                                                'eng_description' => function ($q) {
                                                    $q->select('option_id', 'language_id', 'name');
                                                }
                                            ]);
                                        },
                                        'option_value' => function ($q) {
                                            $q->select('id', 'image', 'sort_order')->with([
                                                'eng_description' => function ($q) {
                                                    $q->select('option_value_id', 'language_id', 'name');
                                                }
                                            ]);
                                        }
                                    ])
                                        ->where('option_id', '1');
                                }
                            ]);
                        }
                    ])
                    ->where('type', getConstant('PRODUCTS_WITHOUT_ADMIN_LVL'))
                    ->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'))->limit(8);
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->where('parent_id', 0)
            ->having('products_count', '>', 0)
            ->orderBy('sort_order', 'ASC')
            ->limit('2')
            ->get();
    }

    function _getAllCategoriesForFrontend()
    {
        return Category::select('id', 'slug', 'parent_id', 'status', 'is_deleted')->withCount([
            'products' => function ($q) {
                $q->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            }
        ])->with([
            'eng_description' => function ($q) {
                $q->select('category_id', 'language_id', 'name');
            },
            'children' => function ($q) {
                $q->withCount([
                    'products' => function ($q) {
                        $q->where('is_deleted', getConstant('IS_NOT_DELETED'))
                            ->where('status', getConstant('IS_STATUS_ACTIVE'));
                    }
                ])->with([
                    'eng_description' => function ($q) {
                        $q->select('category_id', 'language_id', 'name');
                    },
                ])
                    ->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->where('parent_id', 0)
            ->orderBy('sort_order', 'ASC')
            ->get();
    }

    function _updateSlug($request, $id)
    {
        return self::where('id', $id)->update(['slug' => $request->slug . "-" . $id]);
    }
}
