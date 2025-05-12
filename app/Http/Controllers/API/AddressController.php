<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function address()
    {
        $user = Auth::user();
        if ($user->addresses && $user->addresses->isNotEmpty()) {
            return response()->json([
                'address' => $user->addresses
            ]);
        }
        return response()->json([
            'address' => 'There is no address'
        ]);
    }

    public function store_address(Request $request){
        $user = Auth::user();
        $validated = $request->validate([
            'address' => 'nullable|string|max:255',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_email' => 'nullable|email|unique:addresses,receiver_email',
            'phone' => 'nullable|string|max:11',
        ]);

        $address = new Address();
        $address->user_id = $user->id;
        if(!empty($validated['address'])){
            $address->address = $validated['address'];
        }
        if(!empty($validated['receiver_name'])){
            $address->receiver_name = $validated['receiver_name'];
        }
        if(!empty($validated['receiver_email'])){
            $address->receiver_email = $validated['receiver_email'];
        }
        if(!empty($validated['phone'])){
            $address->phone = $validated['phone'];
        }
        $address->save();
        
        return response()->json([
            'address'=>$address
        ]);
    }

    public function update_address(Request $request , Address $address){
        $validated = $request->validate([
            'address' => 'nullable|string|max:255',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_email' => 'nullable|email|unique:addresses,receiver_email',
            'phone' => 'nullable|string|max:11',
        ]);
        if(!empty($validated['address'])){
            $address->address = $validated['address'];
        }
        if(!empty($validated['receiver_name'])){
            $address->receiver_name = $validated['receiver_name'];
        }
        if(!empty($validated['receiver_email'])){
            $address->receiver_email = $validated['receiver_email'];
        }
        if(!empty($validated['phone'])){
            $address->phone = $validated['phone'];
        }
        $address->save();
        
        return response()->json([
            'address'=>$address
        ]);
    }

    public function delete_address(Request $request , Address $address){
        $address->delete();
        return response()->json([
            'message'=>'address deleted'
        ]);
    }
}
