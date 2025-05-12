<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AminUserController extends Controller
{
    public function admin_users_index(){
        $users = User::all();
        if($users->isEmpty()){
            return response()->json([
                'message'=>'there is no users'
            ],404);
        }
        return response()->json([
            'users'=>$users
        ],200);
    }

    public function admin_users_update(Request $request , User $user){
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        if(is_null($user->email_verified_at)){
            return response()->json([
                'message' => 'The user must verify their email.'
            ], 403); 
        }
        $user->role = $request->role;
        $user->save();
        return response()->json([
            'user' => $user,
            'message'=>'user has updated'
        ], 200);
    }

    public function admin_users_destroy(User $user){
        if(is_null($user->email_verified_at) && $user->role == 'user'){
            $user->delete();
            return response()->json([
                'user' => $user,
                'message'=>'user has deleted'
            ], 200);
        }
        return redirect()->route('admin.users.index');
    }
}
