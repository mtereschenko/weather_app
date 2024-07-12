<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $token = Str::random(32);
        Token::create([
            'token' => $token
        ]);

        return response()->json(['token' => $token]);
    }
}
