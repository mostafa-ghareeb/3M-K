<?php

use App\Http\Controllers\ADMIN\AminCouponController;
use App\Http\Controllers\ADMIN\AminOrderController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\MailController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentOrderController;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\AUTH\AuthController;
use App\Http\Controllers\AUTH\EmailVerification;
use App\Http\Controllers\AUTH\ForgetPasswordController;
use App\Http\Controllers\AUTH\GoogleController;
use App\Http\Controllers\AUTH\ResetPasswordController;
use App\Http\Controllers\ADMIN\AminUserController;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Auth
Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);
Route::post('/logout' , [AuthController::class , 'logout'])->middleware('auth:sanctum');
Route::post('/refresh/token' , [AuthController::class , 'refresh_token'])->middleware('auth:sanctum');

//email/verification
Route::post('/email/verification' , [EmailVerification::class , 'email_verification']);
Route::post('send/email/verification' , [EmailVerification::class , 'sendEmailVerification']);

//reset/password
Route::post('forget/password' , [ForgetPasswordController::class , 'forget_password']);
Route::post('reset/password' , [ResetPasswordController::class , 'reset_password']);


//google auth
Route::get('/auth/google', [GoogleController::class,'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class,'handelCallBack']);

//profile
Route::get('/profile' , [ProfileController::class , 'user'])->middleware('auth:sanctum');
Route::post('/update/profile' , [ProfileController::class , 'update_user'])->middleware('auth:sanctum');

//address
Route::get('/address',[AddressController::class,'address'])->middleware('auth:sanctum');
Route::post('/store/address',[AddressController::class,'store_address'])->middleware('auth:sanctum');
Route::put('/update/address/{address}',[AddressController::class,'update_address'])->middleware('auth:sanctum');
Route::delete('/delete/address/{address}',[AddressController::class,'delete_address'])->middleware('auth:sanctum');

//Cart
Route::get('/cart',[CartController::class,'cart'])->middleware('auth:sanctum')->name('cart');
Route::post('/add/product/quantity/to/cart/{product}',[CartController::class,'add_product_quantity_to_cart'])->middleware('auth:sanctum');
Route::post('/add/product/to/cart/{product}',[CartController::class,'add_product_to_cart'])->middleware('auth:sanctum');
Route::post('delete/one/product/from/cart/{product}',[CartController::class,'delete_one_product_from_cart'])->middleware('auth:sanctum');
Route::post('delete/one/product/quantity/from/cart/{product}',[CartController::class,'delete_one_product_quantity_from_cart'])->middleware('auth:sanctum');
Route::delete('delete/cart',[CartController::class,'delete_cart'])->middleware('auth:sanctum');

//order
Route::get('/orders' , [OrderController::class,'orders'])->middleware('auth:sanctum');
Route::get('/order/item/{order_id}' , [OrderController::class,'order_item'])->middleware('auth:sanctum');
Route::post('/store/order' , [OrderController::class,'store_order'])->middleware('auth:sanctum');
Route::delete('/delete/order/{order}' , [OrderController::class,'delete_order'])->middleware('auth:sanctum');


//paypal
Route::post('/payment/{order}' , [PaypalController::class,'payment']);
Route::get('/payment/success/{order_id}' , [PaypalController::class,'payment_success'])->name('payment.success');
Route::get('/payment/cancel' , [PaypalController::class,'payment_cancel'])->name('payment.cancel');

//stripe
Route::post('/create/checkout/session/{order_id}',[StripeController::class,'pay'])->middleware('auth:sanctum');
Route::get('/payment/stripe/success',[StripeController::class,'payment_success'])->name('payment.stripe.success');

//chatbot
Route::post('/chat/bot' , ChatController::class)->middleware('auth:sanctum');


//coupon
Route::get('/coupons' , [StripeController::class,'coupons'])->middleware('auth:sanctum' , 'CheckAdmin');
Route::post('add/coupon' , [StripeController::class,'add_coupon'])->middleware('auth:sanctum' , 'CheckAdmin');


//rate
Route::post('/store/rate/{product}/{order}' , [RateController::class,'store_rate'])->middleware('auth:sanctum');
Route::delete('/dalete/rate/{rate_id}' , [RateController::class,'delete_rate'])->middleware('auth:sanctum');
Route::put('/update/rate/{rate}' , [RateController::class,'update_rate'])->middleware('auth:sanctum');

//payment

Route::post('/payment/{order}' , [PaymentOrderController::class , 'verifyPayment'])->middleware('auth:sanctum');

Route::post('/send/mail' , [MailController::class , 'sendmail'])->middleware('auth:sanctum');

Route::get('/product',[ProductController::class,'show_all']);



//admin user
Route::get('/admin/users/index', [AminUserController::class, 'admin_users_index'])->middleware('auth:sanctum','CheckAdmin');
Route::put('/admin/users/update/{user}', [AminUserController::class, 'admin_users_update'])->middleware('auth:sanctum','CheckAdmin');
Route::delete('/admin/users/delete/{user}', [AminUserController::class, 'admin_users_destroy'])->middleware('auth:sanctum','CheckAdmin');

//admin order
Route::get('/admin/orders/index', [AminOrderController::class, 'admin_orders_index'])->middleware('auth:sanctum','CheckAdmin');
Route::get('/admin/orders/details/index/{order}', [AminOrderController::class, 'admin_order_details'])->middleware('auth:sanctum','CheckAdmin');
Route::delete('/admin/delete/orders/index/{order}', [AminOrderController::class, 'admin_order_destroy'])->middleware('auth:sanctum','CheckAdmin');

//admin coupons
Route::get('/admin/coupons/index', [AminCouponController::class, 'admin_coupons_index'])->middleware('auth:sanctum','CheckAdmin');
Route::post('/admin/coupons/store', [AminCouponController::class, 'admin_coupons_store'])->middleware('auth:sanctum','CheckAdmin');
Route::put('/admin/coupons/update/{coupon}', [AminCouponController::class, 'admin_coupons_update'])->middleware('auth:sanctum','CheckAdmin');
Route::delete('/admin/coupons/delete/{coupon}', [AminCouponController::class, 'admin_coupons_delete'])->middleware('auth:sanctum','CheckAdmin');