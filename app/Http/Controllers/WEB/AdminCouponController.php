<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class AdminCouponController extends Controller
{
    public $stripe;
    public function __construct(){
        $this->stripe = new StripeClient(
            config('stripe.api_key.secret')
        );
    }
    public function admin_coupons_index(){
        $coupons = Coupon::all();
        return view('admin.coupon.index',compact('coupons'));
    }

    public function admin_coupons_create(){
        return view('admin.coupon.create');
    }

    public function admin_coupons_store(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|in:once,forever,repeating',
            'percent_off' => 'required|numeric|min:0|max:100',
            'coupon_number' => 'required|integer|min:1|max:100',
        ]);
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
        }
        return redirect()->route('admin.coupons.index');
    }
    
    public function admin_coupons_edit(Coupon $coupon){
        if($coupon->is_used==true){
            return redirect()->route('admin.coupons.index');
        }
        return view('admin.coupon.edit',compact('coupon'));
    }

    public function admin_coupons_update(Request $request , Coupon $coupon){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|in:once,forever,repeating',
            'percent_off' => 'required|numeric|min:0|max:100',
        ]);
        $this->stripe->coupons->delete($coupon->coupon_id);
        $coupon_id = $this->stripe->coupons->create([
            'duration' => $fields['duration'],
            'percent_off' => $fields['percent_off'],
        ])->id;
        $coupon->name = $fields['name'];
        $coupon->coupon_id = $coupon_id;
        $coupon->discount = $fields['percent_off'];
        $coupon->duration = $fields['duration'];
        $coupon->save();
        
        return redirect()->route('admin.coupons.index');
    }
    
    public function admin_coupons_delete(Coupon $coupon){
        $this->stripe->coupons->delete($coupon->coupon_id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index');
    }
}
