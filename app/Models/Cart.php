<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity' , 'total'];
    //relation with user one to many
    public function user(){
        return $this->belongsTo(User::class);
    }
    //relation with product one to many
    public function product(){
        return $this->belongsTo(Product::class , 'product_id');
    }
}
