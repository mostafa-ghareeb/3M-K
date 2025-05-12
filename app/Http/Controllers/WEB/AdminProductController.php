<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminProductController extends Controller
{
    public function admin_products_index(){
        $products = Product::all();
        return view('admin.product.index',compact('products'));
    }

    public function admin_products_create(){
        return view('admin.product.create');
    }

    public function admin_products_delete(Product $product){
        $product->delete();
        return redirect()->route('admin.products.index');
    }

    public function admin_products_store(Request $request){
        $response = Http::withHeaders([
            'Authorization' => $request->user()->personal_access_tokens->token,
            'Accept' => 'application/json',
        ])->post('');
    }
}
