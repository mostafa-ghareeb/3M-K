<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AminOrderController extends Controller
{
    public function admin_orders_index(){
        $orders = Order::all();
        if(is_null($orders)){
            return response()->json([
                'message'=>'there is no orders'
            ],404);
        }

        return response()->json([
                'orders'=>$orders
            ],200);
    }
    
    public function admin_order_details(Order $order){
        if(is_null($order)){
            return response()->json([
                'message'=>'there is no order detail'
            ],404);
        }
        return response()->json([
                'order_detail'=>$order->order_detail
            ],200);
    }

    public function admin_order_destroy(Order $order){
        if($order->status == 'pending'){
            $order->delete();
            return response()->json([
                'order'=>$order,
                'message'=>'order has been deleted'
            ],200);
        }
        return response()->json([
                'order'=>$order,
                'message'=>'order can not delete this order'
            ],403);
    }
}
