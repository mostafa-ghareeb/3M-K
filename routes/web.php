<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WEB\AdminCouponController;
use App\Http\Controllers\WEB\AdminOrderController;
use App\Http\Controllers\WEB\AdminProductController;
use App\Http\Controllers\WEB\AdminUserController;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $products = Product::all();
    $orders = Order::all();
    $revenue = Order::where('status','complete')->sum('total'); 
    $users = User::all();

    return view('dashboard',compact('products','orders' , 'users' ,'revenue'));
})->middleware(['auth', 'verified','CheckAdmin'])->name('dashboard');

Route::middleware(['auth','CheckAdmin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//product
Route::middleware(['auth','CheckAdmin'])->group(function () {
    Route::get('/admin/products/index', [AdminProductController::class, 'admin_products_index'])->name('admin.products.index');
    Route::get('/admin/products/create', [AdminProductController::class, 'admin_products_create'])->name('products.create');
    Route::post('/admin/products/store', [AdminProductController::class, 'admin_products_store'])->name('products.store');
    Route::delete('/admin/products/delete/{product}', [AdminProductController::class, 'admin_products_delete'])->name('products.destroy');
});
//order
Route::middleware(['auth','CheckAdmin'])->group(function () {
    Route::get('/admin/orders/index', [AdminOrderController::class, 'admin_orders_index'])->name('admin.orders.index');
    Route::get('/admin/orders/details/index/{order}', [AdminOrderController::class, 'admin_order_details'])->name('order.details');
    Route::delete('/admin/delete/orders/index/{order}', [AdminOrderController::class, 'admin_order_destroy'])->name('order.destroy');
});
//user
Route::middleware(['auth','CheckAdmin'])->group(function () {
    Route::get('/admin/users/index', [AdminUserController::class, 'admin_users_index'])->name('admin.users.index');
    Route::get('/admin/users/edit/{user}', [AdminUserController::class, 'admin_users_edit'])->name('user.edit');
    Route::put('/admin/users/update/{user}', [AdminUserController::class, 'admin_users_update'])->name('users.updateRole');
    Route::delete('/admin/users/delete/{user}', [AdminUserController::class, 'admin_users_destroy'])->name('user.destroy');
});
//coupons
Route::middleware(['auth','CheckAdmin'])->group(function () {
    Route::get('/admin/coupons/index', [AdminCouponController::class, 'admin_coupons_index'])->name('admin.coupons.index');
    Route::get('/admin/coupons/create', [AdminCouponController::class, 'admin_coupons_create'])->name('admin.coupons.create');
    Route::post('/admin/coupons/store', [AdminCouponController::class, 'admin_coupons_store'])->name('admin.coupons.store');
    Route::get('/admin/coupons/edit/{coupon}', [AdminCouponController::class, 'admin_coupons_edit'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/update/{coupon}', [AdminCouponController::class, 'admin_coupons_update'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/delete/{coupon}', [AdminCouponController::class, 'admin_coupons_delete'])->name('admin.coupons.destroy');
});

require __DIR__.'/auth.php';
