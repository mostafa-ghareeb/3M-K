<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['user_id', 'product_id', 'order_id', 'rating', 'comment'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class , 'product_id' , 'Id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

}
