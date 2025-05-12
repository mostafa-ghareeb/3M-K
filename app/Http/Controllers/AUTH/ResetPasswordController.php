<?php

namespace App\Http\Controllers\AUTH;

use App\Http\Controllers\Controller;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{

    private $otp;

    public function __construct(){
        $this->otp = new Otp;
    }
    public function reset_password(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'otp' => 'required|max:6',
            'password' => [
                'required',
                'min:8',
                'confirmed',    
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ]);

        $check_otp = $this->otp->validate($request->email , $request->otp);
        if(!$check_otp->status){
            return response()->json([
                'error' => $check_otp
            ] , 401);
        }
        $user = User::where('email' , $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        $user->tokens()->delete();
        return response()->json([
            'data' => 'Password have been changed'
        ] , 200);
        
    }
}
