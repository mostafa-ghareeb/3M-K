<?php


use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MailController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\AUTH\AuthController;
use App\Http\Controllers\AUTH\EmailVerification;
use App\Http\Controllers\AUTH\ForgetPasswordController;
use App\Http\Controllers\AUTH\GoogleController;
use App\Http\Controllers\AUTH\ResetPasswordController;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\User;
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
Route::post('/email/verification' , [EmailVerification::class , 'email_verification']);
Route::post('send/email/verification' , [EmailVerification::class , 'sendEmailVerification']);
Route::post('forget/password' , [ForgetPasswordController::class , 'forget_password']);
Route::post('reset/password' , [ResetPasswordController::class , 'reset_password']);


//google auth
Route::get('/auth/google', [GoogleController::class,'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class,'handelCallBack']);

//profile
Route::get('/profile' , [ProfileController::class , 'user'])->middleware('auth:sanctum');
Route::post('/update/profile' , [ProfileController::class , 'update_user'])->middleware('auth:sanctum');

Route::post('/send/mail' , [MailController::class , 'sendmail'])->middleware('auth:sanctum');

//Categorie
Route::get('/categories' , [CategoryController::class , 'index'])->middleware('auth:sanctum');
Route::post('/store/category' , [CategoryController::class , 'store']);
Route::put('/update/category/{category}' , [CategoryController::class , 'update']);
Route::delete('/delete/category/{category}' , [CategoryController::class , 'delete']);

//Product
Route::get('/products' , [ProductController::class , 'index'])->middleware('auth:sanctum');
Route::post('/store/product' , [ProductController::class , 'store']);
Route::put('/update/product/{product}' , [ProductController::class , 'update']);
Route::delete('/delete/product/{product}' , [ProductController::class , 'delete']);

