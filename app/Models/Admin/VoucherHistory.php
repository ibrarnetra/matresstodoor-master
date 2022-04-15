<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherHistory extends Model
{
    use HasFactory;

    public function _insert($voucher_id, $order_id, $customer_id, $amount)
    {
        $voucher_history = new VoucherHistory();
        $voucher_history->voucher_id = $voucher_id;
        $voucher_history->order_id = $order_id;
        $voucher_history->customer_id = $customer_id;
        $voucher_history->amount = $amount;
        $voucher_history->save();
    }
}
