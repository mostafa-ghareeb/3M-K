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
            'phone' => 'required|string|regex:/^[0-9]{11}$/|unique:users,phone',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'gender' => 'required|string|in:male,female,other',
        ]);
        if($request->hasFile('image')){
            $image = $request->file('image')->getClientOriginalName();
            $cleanedName = str_replace(' ', '_', $image);
            $filename = time() . '_' . $cleanedName;
            $path = $request->file('image')->storeAs('usersphoto' , $filename , 'userimage');
        }
        $user = new User();
        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->password = bcrypt($fields['password']);
        $user->phone = $fields['phone'];
        $user->image = 'usersimages/'.$path;
        $user->gender = $fields['gender'];
        $user->save();
        return response()->json([
            'data'=> 'do Email Verification',
        ],200);
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
                'error' => 'the provided credentials are incorrect.'
            ]);
        }elseif($user){
            $user->tokens()->delete();
            $success['token'] = $user->createToken($user->name)->plainTextToken;
            $success['user'] = $user;
            $user->notify(new LoginNotification());
            return response()->json($success,200);
        }
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'you are logged out.'
        ],200);
    }

    // public function reset_password(Request $request){
    //     $request->validate([
    //         'password' => [
    //             'required',
    //             'confirmed',
    //             'min:8',
    //             'regex:/[a-z]/',
    //             'regex:/[A-Z]/',
    //             'regex:/[0-9]/',
    //             'regex:/[@$!%*?&]/',
    //         ],
    //     ]);
    //     $user = User::where('id' , Auth::user()->id)->first();
    //     if (Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'message' => 'The new password cannot be the same as the old password.',
    //         ]);
    //     }
    //     $user->password = Hash::make($request->password);
    //     $user->save();
    //     return response()->json([
    //         'message' => 'Password changed successfully.',
    //     ]);
    
    // }

    
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
