<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function user(Request $request){
        $user = $request->user();
        if($user->image==NULL){
            return response()->json([
                'user'=>$user,
                'image url' =>NULL,
                'addresses'=>$user->addresses()
            ],200);
        }
        return response()->json([
            'user'=>$user,
            'image url' =>asset($user->image),
            'addresses'=>$user->addresses()
        ],200);
    }

    public function update_user(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{11}$/|unique:users,phone,'.$request->user()->id,
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|string|in:male,female,other',
        ]);
        
        $user = $request->user();
        if($request->hasFile('image')){
            $image = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('usersphoto' , $image , 'userimage');
        }
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];
        $user->image ='usersimages/'.$path;
        $user->gender = $validatedData['gender'];
        $user->save();
        return response()->json([
            'user'=>$user,
            'image url' =>asset($user->image),
        ],200);
    }
}
