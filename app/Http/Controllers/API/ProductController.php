<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json([
            'data' => $products,
            'image url' =>asset($products->image),
        ],200);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'string|max:100',
            'model_year' => 'required|integer|min:1900|max:' . date('Y'),
            'size' => 'required|integer|min:1',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);
        $product = new Product();

        if($request->hasFile('image')){
            $image = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('productsphoto' , $image , 'productimage');
            $product->image = 'productsimages/'.$path;
        }
        if($validatedData['color']){
            $product->color = $validatedData['color'];
        }
        $product->category_id = $validatedData['category_id'];
        $product->name = $validatedData['name'];
        $product->model_year = $validatedData['model_year'];
        $product->size = $validatedData['size'];
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->save();
        return response()->json([
            'data' => $product ,
            'image url' =>asset($product->image),
            'message' => 'Product is saved.',
        ] , 200);
    }

    public function delete(Product $product){
        $product->delete();
        return response()->json([
            'data' => $product ,
            'message' => $product->name.' is deleted.',
        ] , 200);
    }
    public function update(Request $request , Product $product){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'string|max:100',
            'model_year' => 'required|integer|min:1900|max:' . date('Y'),
            'size' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('productsphoto' , $image , 'productimage');
            $product->image = 'productsimages/'.$path;
        }
        if($validatedData['color']){
            $product->color = $validatedData['color'];
        }
        $product->category_id = $validatedData['category_id'];
        $product->name = $validatedData['name'];
        $product->color = $validatedData['color'];
        $product->model_year = $validatedData['model_year'];
        $product->size = $validatedData['size'];
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->save();
        return response()->json([
            'data' => $product ,
            'image url' =>asset($product->image),
            'message' => 'Product is updated.',
        ] , 200);
    }
}
