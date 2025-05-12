<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class AdminUserController extends Controller
{
    public function admin_users_index(){
        $users = User::all();
        return view('admin.user.index',compact('users'));
    }

    public function admin_users_edit(User $user){
        if(!is_null($user->email_verified_at) && $user->role == 'user'){
            return view('admin.user.edit',compact('user'));
        }
        return redirect()->route('admin.users.index');
    }

    public function admin_users_update(Request $request , User $user){
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        if(!is_null($user->email_verified_at)){
            $user->role = $request->role;
            $user->save();
        }
        return redirect()->route('admin.users.index');
    }

    public function admin_users_destroy(User $user){
        if(isEmpty($user->email_verified_at) && $user->role == 'user'){
            $user->delete();
            return redirect()->route('admin.users.index');
        }
        return redirect()->route('admin.users.index');
    }
}
