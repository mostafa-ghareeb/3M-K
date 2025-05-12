<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentOrderController extends Controller
{
    public function verifyPayment(Request $request , Order $order){
        $request->validate([
            'transaction_id' => 'required|string',
            'payment_method' => 'required|string|in:paypal,paymob',
        ]);
        if($order->user_id !=$request->user()->id){
            return response()->json(['message' => 'this order not belong to thus user'], 400);
        }
        $transactionid = $request->transaction_id;
        $paymentmethod = $request->payment_method;
        if ($paymentmethod == 'paypal') {
            $verified = $this->verifyPaypal($transactionid);
        }else if($paymentmethod == 'paymob'){
            $verified = $this->verifyPaymob($transactionid);
        }else{
            return response()->json(['message' => 'Invalid payment method'], 400);
        }

        if ($verified) {
            $order->status = 'complete';
            $order->payment_method = $paymentmethod;
            $order->transaction_id = $transactionid;
            $order->paid_at = now();
            $order->save();
            return response()->json(['message' => 'Payment verified and order updated.']);
        }
        return response()->json(['message' => 'Payment verification failed.'], 400);
    }
    private function verifyPaypal($transactionid){
        $clientId = env('PAYPAL_CLIENT_ID');
        $secret = env('PAYPAL_SECRET');
        $baseUrl = 'https://api-m.sandbox.paypal.com';
        $tokenResponse = Http::withBasicAuth($clientId, $secret)
        ->asForm()
        ->post("$baseUrl/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ]);

        if (!$tokenResponse->ok()) return false;

        $accessToken = $tokenResponse['access_token'];

        $paymentResponse = Http::withToken($accessToken)
            ->get("$baseUrl/v1/payments/payment/$transactionid");

        return collect($paymentResponse['transactions'][0]['related_resources'] ?? [])
        ->pluck('sale.state')
        ->contains('completed');
    }
    private function verifyPaymob($transactionid){
        $apiKey = 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRBek5EZ3hNQ3dpYm1GdFpTSTZJbWx1YVhScFlXd2lmUS5wVkVyc3FlYW0yaThNSEhteENna2lodXlZektiYU5DZHVzTG9iQXBaaE5GaEVSQkY3eGxtRW1rOWJpN3lPZUVGeHFXeXZ6OWp1aFNoazdXMGl6Q1JsZw==';
        $url = 'https://accept.paymob.com/api/auth/tokens';
        $tokenResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'api_key' => $apiKey,
        ]);
        if (!$tokenResponse->successful()) {
            return false;
        }
        $accessToken = $tokenResponse['token'];
        $paymentResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])
        ->get("https://accept.paymob.com/api/acceptance/transactions/$transactionid");
        if (!$paymentResponse->successful()) {
            return false;
        }
        $paymentData = $paymentResponse->json();
        $status = $paymentData['success'] ?? false;
    
        return $status === true;
    
    }
}
