<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function admin_orders_index(){
        $orders = Order::all();
        return view('admin.order.index',compact('orders'));
    }
    
    public function admin_order_details(Order $order){
        return view('admin.order.details',compact('order'));
    }

    public function admin_order_destroy(Order $order){
        if($order->status == 'pending'){
            $order->delete();
            return redirect()->route('admin.orders.index');
        }
        return redirect()->route('admin.orders.index');
    }
}
