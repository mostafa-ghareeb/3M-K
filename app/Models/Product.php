<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'Products'; 

    protected $primaryKey = 'Id'; 

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'Id','Name', 'Description', 'PictureUrl', 'UrlGlb',
        'Price', 'ProductBrandId', 'ProductTypeId', 'Quantity' , 'isFav' , 'isLike'
    ];

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function order_detail(){
        return $this->hasMany(OrderDetail::class);
    }

    public function getPictureUrlAttribute($value)
    {
        return 'https://bazvfoiiqfamubdjqgoi.supabase.co/storage/v1/object/public/' . $value;
    }
    public function getUrlGlbAttribute($value)
    {
        return 'https://bazvfoiiqfamubdjqgoi.supabase.co/storage/v1/object/public/' . $value;
    }
}
