<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'email', 'address', 'phone' , 'total' , 'note' , 'user_id'];
    public function order_detail(){
        return $this->hasMany(OrderDetail::class , );
    }
}
