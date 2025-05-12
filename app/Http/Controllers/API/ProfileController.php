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
            'image url' =>'https://concise-ant-sound.ngrok-free.app/'.$user->image,
            'addresses'=>$user->addresses()
        ],200);
    }

    public function update_user(Request $request){
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|regex:/^[0-9]{11}$/|unique:users,phone,'.$request->user()->id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,avif|max:20480',
            'gender' => 'string|in:male,female,other',
        ]);
        
        $user = $request->user();
        if($request->hasFile('image')){
            $image = $request->file('image')->getClientOriginalName();
            $cleanedName = str_replace(' ', '_', $image);
            $filename = time() . '_' . $cleanedName;
            $path = $request->file('image')->storeAs('usersphoto' , $filename , 'userimage');
        }
        if( !empty($validatedData['name'])){
            $user->name = $validatedData['name'];
        }
        if(!empty($validatedData['phone'])){
            $user->phone = $validatedData['phone'];
        }
        if ($request->hasFile('image')) {
            $user->image ='usersimages/'.$path;
        }
        if(!empty($validatedData['gender'])){
            $user->gender = $validatedData['gender'];
        }
        $user->save();
        return response()->json([
            'user'=>$user,
            'image url' =>asset($user->image),
        ],200);
    }
}

