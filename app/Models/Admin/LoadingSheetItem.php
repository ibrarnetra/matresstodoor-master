<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Product;
use App\Models\Admin\OrderProduct;
use App\Models\Admin\Order;
use App\Models\Admin\LoadingSheet;

class LoadingSheetItem extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function loading_sheet()
    {
        return $this->belongsTo(LoadingSheet::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function loading_sheet_item_options()
    {
        return $this->hasMany(LoadingSheetItemOption::class);;
    }
}
