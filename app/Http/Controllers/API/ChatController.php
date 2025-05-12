<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
        ]);
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . env('OPENROUTER_KEY'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            "model" => "mistralai/mixtral-8x7b-instruct",
            "messages" => [
                ["role" => "user", "content" => $validatedData['content']]
            ],
            'stream' => false,
        ])->json();
        
        return response()->json([
            'message' => $response
        ],200);
    }
}
