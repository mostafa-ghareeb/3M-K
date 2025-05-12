<?php

namespace App\Http\Controllers\AUTH;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forget_password(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $user = User::where('email' , $request->email)->first();
        if(!$user){
            return response()->json([
                'data' => 'this email is not correct'
            ],401);
        }
        $user->notify(new ResetPasswordVerificationNotification());
        return response()->json([
            'data' => 'code have been sended'
        ],200);
    }
}
