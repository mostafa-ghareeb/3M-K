<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart(Request $request){
        $cart_products = Cart::with('product')
        ->where('user_id',$request->user()->id)
        ->where('quantity','>',0)
        ->get();
        if($cart_products->isEmpty()){
            return response()->json('there is no card',404);
        }

        foreach($cart_products as $cart_product){
            $cart_product['product']->base_url_image=$cart_product->product->PictureUrl;
            $cart_product['product']->base_url_glb_image=$cart_product->product->UrlGlb;
        }
        $total_cart = $cart_products->sum('total');
        return response()->json([
            'cart' =>$cart_products,
            'total cart' =>$total_cart,
        ],200);

    }
    public function add_product_quantity_to_cart(Request $request,Product $product){
        if($product->Quantity <= 0){
            return response()->json([
                'message' => 'this Product Quantity Zero'
            ],400);
        }
        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$product->Quantity,
        ]);
        $cart = Cart::where('user_id',$request->user()->id)
        ->where('product_id',$product->Id)
        ->first();
        if($cart){
            if( ($cart->quantity + $request->quantity) > $product->Quantity){
                return response()->json([
                    'message' => "You cannot add these items, the maximum is ".$product->Quantity
                ],400);
            }
            $cart->quantity +=$request->quantity;
            $cart->total +=$request->quantity*$product->Price;
            $cart->save();
            return response()->json([
                'message' =>$cart
            ],200);
        }else{
            $cart = new Cart();
            $cart->user_id = $request->user()->id;
            $cart->product_id = $product->Id;
            $cart->quantity = $request->quantity;
            $cart->total = $request->quantity * $product->Price;
            $cart->save();
        }

        return response()->json([
            'message' =>$cart   
        ],200);
    }

    public function add_product_to_cart(Request $request , Product $product){
        if($product->Quantity <= 0){
            return response()->json([
                'message' => 'this Product Quantity Zero'
            ],400);
        }
        $cart = Cart::where('user_id',$request->user()->id)
        ->where('product_id',$product->Id)
        ->first();
        if($cart){
            if( ($cart->quantity + 1) > $product->Quantity){
                return response()->json([
                    'message' => "You cannot add these items, the maximum is ".$product->Quantity
                ],400);
            }
            $cart->quantity +=1;
            $cart->total +=$request->quantity*$product->Price;
            $cart->save();
            return response()->json([
                'message' =>$cart
            ],200);
        }else{
            $cart = new Cart();
            $cart->user_id = $request->user()->id;
            $cart->product_id = $product->Id;
            $cart->quantity = 1;
            $cart->total = $product->Price;
            $cart->save();
        }
        return response()->json([
            'message' =>$cart
        ],200);
    }
    public function delete_one_product_from_cart(Request $request , Product $product){
        if($product->Quantity <= 0){
            return response()->json([
                'message' => 'this Product Quantity Zero'
            ],400);
        }
        $cart = Cart::where('user_id',$request->user()->id)
        ->where('product_id',$product->Id)
        ->with('product')
        ->first();
        if($cart && ( $cart->quantity > 0 )){
            $cart->quantity -=1;
            $cart->total -=$product->Price;
            $cart->save();
            if($cart->quantity == 0){
                $cart->delete();
                return response()->json([
                    'message' =>'there is no product to delete'
                ],200);
            }
            return response()->json([
                'message' =>$cart
            ],200);
        }
        return response()->json([
            'message' =>'there is no product to delete'
        ],400);
    }
    public function delete_cart(Request $request){
        $request->user()->cart()->delete();
        return response()->json([
            'message' =>'cart delete successful'
        ],200); 
    }

    public function delete_one_product_quantity_from_cart(Request $request , Product $product){
        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$product->Quantity,
        ]);

        $cart = Cart::where('user_id',$request->user()->id)
        ->where('product_id',$product->Id)
        ->first();

        if($cart){
            if(($cart->quantity - $request->quantity) <=0){
                $cart->delete();
                $cartitems = Cart::where('user_id',$request->user()->id)
                ->with('product')
                ->get();
                return response()->json([
                    'message'=>'the product '.$product->Name.' was deleted from cart',
                    'cart'=>$cartitems
                ]);
            }else{
                $cart->quantity -= $request->quantity;
                $cart->total = max(0, $cart->total - ($request->quantity * $product->Price));
                $cart->save();
                $cartitems = Cart::where('user_id',$request->user()->id)
                ->with('product')
                ->get();
                return response()->json([
                    'message'=>'the product '.$product->Name.' was updated in cart',
                    'cart'=>$cartitems
                ]);
            }
        }

        return response()->json([
            'message'=> 'there is no cart for you'
        ]);
    }
}
