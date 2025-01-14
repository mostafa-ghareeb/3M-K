<?php

namespace App\Http\Controllers\AUTH;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class EmailVerification extends Controller
{
    private $otp;

    public function __construct(){
        $this->otp = new Otp;
    }

    public function sendEmailVerification(Request $request){
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'user'=>'you must be register'
            ],401); 
        }
        $user->notify(new EmailVerificationNotification());
        return response()->json([
            'data' => 'code otp have been sended',
        ],200);
    }
    
    public function email_verification(Request $request){
        $fields = $request->validate([
            'email' => 'required|email|exists:users',
            'otp' => 'required|max:6',
        ]);

        $check_otp = $this->otp->validate($fields['email'] , $fields['otp']);
        if(!$check_otp->status){
            return response()->json([
                'error' =>$check_otp
            ],401);
        }
        $user = User::where('email' , $request->email)->first();

        $user->email_verified_at = now();
        $user->save();
        return response()->json([
            'data' =>'Email Verification success'
        ],200);
    }
}
