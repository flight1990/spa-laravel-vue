<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    public function user()
    {
        return auth()->user();
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details.
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(),  'token' => $token]);
    }

    public function logout()
    {
        $user = auth()->user()->token();
        $user->revoke();

        return response('logged out');
    }
}
