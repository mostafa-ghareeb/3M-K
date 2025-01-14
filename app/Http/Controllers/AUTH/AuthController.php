<?php

namespace App\Http\Controllers\AUTH;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\LoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function user(){
        return Auth::user();
    }
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ]);
        User::create($fields);

        //$user->notify(new EmailVerificationNotification());
        return response()->json([
            'data'=> 'do Email Verification'
        ]);
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ]);
        $user = User::where('email' , $request->email)->first();
        if($user->email_verified_at==NULL){
            return response()->json([
                'error'=>'must do Email Verification'
            ]);
        }

        if(!$user || !Hash::check($request->password , $user->password)){
            return response()->json([
                'message' => 'the provided credentials are incorrect.'
            ],401);
        }elseif($user){
            $user->tokens()->delete();
            $success['token'] = $user->createToken($user->name)->plainTextToken;
            $success['user name'] = $user->name;
            $user->notify(new LoginNotification());
            return response()->json($success);
        }
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'you are logged out.'
        ]);
    }

    public function reset_password(Request $request){
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ]);
        $user = User::where('id' , Auth::user()->id)->first();
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The new password cannot be the same as the old password.',
            ]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Password changed successfully.',
        ]);
    
    }

    public function refresh_token(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('refresh_token')->plainTextToken;
        return response()->json([
            'Token' => $token,
            'User' => $user,
            'image url' =>asset($user->image),
        ]);
    }
}
