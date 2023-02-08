<?php

namespace App\Http\Controllers\Api;

use App\Models\UserOtp;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class PassportController extends Controller
{
    public function user()
    {
        return auth()->user();
    }

    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        if (!auth()->attempt($payload)) {
            return response(['errors' => ['password' => ['Credentials does not match']]], 401);
        }

        if (!empty(auth()->user()->banned_at)) {
            return response(['errors' => ['password' => ['Your account is blocked']]], 403);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['token' => $token]);
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();
        $payload['password'] = bcrypt($payload['password']);

        $user = User::create($payload);
        $token = $user->createToken('API Token')->accessToken;

        return response(['token' => $token]);
    }

    public function logout()
    {
        $user = auth()->user();

        UserOtp::where('user_id', $user->id)->delete();

        $user->token()->revoke();

        return response('logged out');
    }
}
