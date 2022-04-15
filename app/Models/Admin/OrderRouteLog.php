<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRouteLog extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $timestamps = false;

    function _insert($order_id, $route_id)
    {
        self::create([
            'order_id' => $order_id,
            'route_id' => $route_id,
        ]);
    }
}
