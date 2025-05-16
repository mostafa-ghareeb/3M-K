<?php
//check the mail is done right
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendmail(Request $request){
        $fields = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        Mail::to((string)Auth::user()->email)->send(new SendMail($request->subject , $request->message));
        return response()->json([
            'data'=>'send mail is success'
        ]);
    }
}
