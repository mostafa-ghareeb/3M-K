<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\StripeClient;

use function PHPUnit\Framework\isEmpty;

class StripeController extends Controller
{
    public $stripe;
    public function __construct(){
        $this->stripe = new StripeClient(
            config('stripe.api_key.secret')
        );
    }

    public function coupons(){
        $coupons = Coupon::where('is_used',false)->get();
        if($coupons->isEmpty()){
            return response()->json([
                'message' => 'Coupons is empty',
            ], 200);
        }
        return response()->json([
            'coupons' => $coupons
        ], 200);
    }
    public function add_coupon(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|in:once,forever,repeating',
            'percent_off' => 'required|numeric|min:0|max:100',
            'coupon_number' => 'required|integer|min:1|max:100',
        ]);
        $coupons =[];
        for ($i=0; $i < $fields['coupon_number']; $i++) {
            $coupon_id = $this->stripe->coupons->create([
                'duration' => $fields['duration'],
                'percent_off' => $fields['percent_off'],
            ])->id;
            $new_coupon = new Coupon();
            $new_coupon->name = $fields['name'];
            $new_coupon->coupon_id = $coupon_id;
            $new_coupon->discount = $fields['percent_off'];
            $new_coupon->duration = $fields['duration'];
            $new_coupon->save();
            $coupons[]= $new_coupon;
        }
        return response()->json([
            'message' => 'Coupons created successfully',
            'coupons' => $coupons
        ], 201);
    }

    public function pay(Request $request , $order_id){
        $fields = $request->validate([
            'coupon_id' => 'nullable|string|max:255',
        ]);
        
        $order = Order::where('user_id',$request->user()->id)
        ->where('id' , $order_id)
        ->first();
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $true_coupon = null;
        if (!empty($fields['coupon_id'])) {
            $coupon = Coupon::where('coupon_id', $fields['coupon_id'])->first();
            if ($coupon && !$coupon->is_used) {
                $true_coupon = $coupon;
            }
        }
        if($order->status =='complete'){
            return response()->json([
                'message' => 'this order payed',
            ], 200);
        }
        
        $line_items=[];
        foreach($order->order_detail as $item){
            $line_items[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->Name,
                            'description' => $item->product->Description ?: 'No description available',
                            'images' =>[$item->product->PictureUrl ? url($item->product->PictureUrl) : 'https://example.com/default-image.jpg']
                        ],
                        'unit_amount' => 100 * $item->product->Price,
                    ],
                    'quantity' => $item->quantity,
                ];
            }
            
        $session_data =[
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:4242/cancel',
            'line_items'=>$line_items,
            'metadata' => [
                'order_id' => $order->id
            ],
        ];

        if ($true_coupon!==null) {
            $session_data['discounts'] = [['coupon' => $true_coupon->coupon_id]];
            $session_data['metadata']['coupon'] = $true_coupon->coupon_id;
        }

        $session = $this->stripe->checkout->sessions->create($session_data);

        return response()->json([
            'id' => $session->id,
            'Session_Url' =>$session->url
        ]);
    }

    public function payment_success(Request $request){

        
        $session = $this->stripe->checkout->sessions->retrieve($request->session_id);
        if (!empty($session->metadata->coupon)) {
            $coupon = Coupon::where('coupon_id', $session->metadata->coupon)->first();
            if ($coupon) {
                $coupon->is_used = true;
                $coupon->save();
            }
        }
        $order = Order::where('id',$session->metadata->order_id)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = 'complete';
        $order->payment_method = 'stripe';
        $order->transaction_id = $session->id;
        $order->paid_at = now();
        $order->save();
        return response()->json(['message' => 'Payment successful', 'order' => $order],200);
    }
}
