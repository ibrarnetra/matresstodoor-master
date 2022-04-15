<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\User;
use App\Models\Admin\Order;

class OrderManagementComment extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    function _store($request)
    {
        $super_admin = User::whereHas("roles", function ($q) {
            $q->where("name", "Super Admin");
        })
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->first();

        return self::create([
            'order_id' => $request->order_id,
            'commented_by' => $request->commented_by,
            'comment' => $request->dispatch_comment,
            'assigned_to' => $request->dispatch_manager_id,
            'assigned_by' => $super_admin->id,
        ]);
    }
}
