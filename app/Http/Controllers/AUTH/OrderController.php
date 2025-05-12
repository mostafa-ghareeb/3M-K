<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store_order(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|regex:/^[0-9]{11}$/',
            'note' => 'nullable|string|max:1000',
        ]);

        $carts = Cart::where('user_id',$request->user()->id)->with('product')->get();

        if($carts->isEmpty()){
            return response()->json(['message' => 'there is no cart to make order'],200);
        }else{
            $new_order = new Order();
            $new_order->name = $fields['name'];
            $new_order->email = $fields['email'];
            $new_order->address = $fields['address'];
            $new_order->phone = $fields['phone'];
            $new_order->note = $fields['note'] ?? null;
            $new_order->user_id = $request->user()->id;
            $new_order->save();

            $order_id = $new_order->id;
            
            foreach($carts as $cart){
                $new_order_detail = new OrderDetail();
                $new_order_detail->product_id = $cart->product_id;
                $new_order_detail->order_id = $new_order->id;
                $new_order_detail->quantity = $cart->quantity;
                $new_order_detail->price = (float) $cart->product->Price;
                $new_order_detail->save();
            }
        }
        Cart::where('user_id',$request->user()->id)->delete();
        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $new_order,
        ], 201);
    }
}
