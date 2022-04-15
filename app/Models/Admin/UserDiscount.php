<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    use HasFactory;

    public function _insert($user_id, $allowed_discount)
    {
         $user_discount = self::where('user_id', $user_id)->first();
      
         if($user_discount)
         {
            $user_discount->allowed_discount = $allowed_discount;
            $user_discount->save();
         }
         else{
             $user_discount = new UserDiscount();
             $user_discount->user_id = $user_id;
             $user_discount->allowed_discount = $allowed_discount;
             $user_discount->save();
         }
    }
}
