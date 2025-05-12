<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{
    public function payment(Order $order){

        if($order->status == 'complete'){
            return response()->json(['message' => 'this order is payed'],200);
        }
        $items = [];
        foreach($order->order_detail as $item){
            $items[] = 
            [
                'name' => $item->product->Name,
                'price' => $item->product->Price,
                'description' => $item->product->Description,
                'qty' => $item->quantity
            ];
        }
        $data = [
            'items' => $items,
            'invoice_id' => 1,
            'invoice_description' => 'Order Invoice',
            'return_url' => route('payment.success', ['order_id' => $order->id]),
            'cancel_url' => route('payment.cancel'),
            'total' => $order->total,
            'order_id' => $order->id, 
            'CUSTOM' => $order->id,
        ];

        $provider = new ExpressCheckout();
        $response = $provider->setExpressCheckout($data, true);

        

        return response()->json([
            'paypal_url' => $response['paypal_link']
        ],200);
        
    }


    public function payment_cancel(){
        return response()->json(['message' => 'payment was cancel']);
    }

        public function payment_success(Request $request , $order_id){
            $provider = new ExpressCheckout();
            $response = $provider->getExpressCheckoutDetails($request->token);

            if (!$order_id) {
                return response()->json(['message' => 'Order ID not found'], 400);
            }
            $order = Order::where('id',$order_id)->first();
            if(in_array(strtoupper($response['ACK']) , ['SUCCESS' , 'SUCCESSWITHWARNING'])){
                $order->status = 'complete';
                $order->payment_method = 'paypal';
                $order->transaction_id = $response['PAYMENTINFO_0_TRANSACTIONID'];
                $order->paid_at = now();
                $order->save();
                return response()->json(['message' => 'payment was success'],200);
            }else{
                return response()->json(['message' => 'please try again later'],400);
            }
        }
}
