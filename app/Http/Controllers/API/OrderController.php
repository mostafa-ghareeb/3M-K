<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders(Request $request){
        $orders = Order::where('user_id',$request->user()->id)
        ->with('order_detail')
        ->get();
        if($orders->isEmpty()){
            return response()->json(['there is no order'],400);
        }
        foreach($orders as $order){
            foreach($order->order_detail as $product){
                $product_name = Product::where('Id',$product->product_id)->first();
                $product['name']=$product_name->Name?? 'Unknown';
            }
        }
        return response()->json(['order' =>$orders ],200);
    }
    public function order_item(Request $request , $order_id){
        $order = Order::where('user_id',$request->user()->id)->
        where('id' , $order_id)
        ->first();
        if(!$order){
            return response()->json(['there is no order'],400);
        }
        $order_detail = OrderDetail::where('order_id' , $order->id)
        ->with('product')
        ->get();
        return response()->json([
            'order' =>$order,
            'items' =>$order_detail, 
        ],200);
    }
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
            $new_order->user_id = $request->user()->id;
            $new_order->total = $carts->sum('total');
            $new_order->status = 'pending';
            $new_order->payment_method = null;
            $new_order->transaction_id = null;
            $new_order->paid_at = null;
            $new_order->note = $fields['note'] ?? null;
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
        ], 200);
    }

    public function delete_order(Request $request , Order $order){
        if(!$order){
            return response()->json(['there is no order'],400);
        }
        $order->delete();
        return response()->json([
            'message' => 'Order Delete'
        ], 200);
    }
}
