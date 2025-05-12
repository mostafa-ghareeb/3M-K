<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function show_all(){
        $products = Product::all();
        foreach($products as $product){
            $product['baseurlimage']='https://bazvfoiiqfamubdjqgoi.supabase.co/storage/v1/object/public/'.$product->PictureUrl;
        }
        return response()->json([
            'products'=>$products
        ]);
    }
}
