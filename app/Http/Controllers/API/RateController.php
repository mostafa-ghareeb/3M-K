<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store_rate(Request $request , Product $product , Order $order){
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        $orderExists = Order::where('user_id',$request->user()->id)
        ->where('id',$order->id)
        ->where('status','complete')
        ->whereHas('order_detail',function ($query) use ($product){
            $query->where('product_id',$product->Id);
        })
        ->exists();
        if(!$orderExists){
            return response()->json([
                'message' => 'The selected order is invalid or does not belong to this product'
            ], 400);
        }
        $rate = new Rate();
        $rate->user_id = $request->user()->id;
        $rate->product_id = $product->Id;
        $rate->order_id = $order->id;
        $rate->rating = $request->rating;
        $rate->comment = $request->comment;
        $rate->save();
        
    return response()->json(['message' => 'Rating submitted successfully'],200);
    }

    public function delete_rate(Rate $rate_id){
        $rate_id->delete();
        return response()->json(['message' => 'Rating deleted successfully'],200);
    }

    public function update_rate(Request $request , Rate $rate){
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        $rate->rating = $request->rating;
        $rate->comment = $request->comment;
        $rate->save();

        return response()->json(['message' => 'Rating updated successfully'],200);
    }
}