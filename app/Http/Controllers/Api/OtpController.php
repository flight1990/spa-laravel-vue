<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\UserOtp;
use App\Models\User;
use App\Http\Requests\OtpLoginRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Controllers\Controller;

class OtpController extends Controller
{
    public function generate(OtpRequest $request)
    {
        $user = User::query()->where('phone', $request->phone)->first();
        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();

        $now = now();

        if($userOtp && $now->isBefore($userOtp->expire_at)){
            return $userOtp;
        }

        return UserOtp::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => $now->addMinutes(10)
        ]);
    }

    public function login(OtpLoginRequest $request)
    {
        $userOtp = UserOtp::where('user_id', $request->user_id)->where('otp', $request->otp)->first();

        $now = now();

        if (!$userOtp) {
            return response(['errors' => ['otp' => ['Your OTP is not correct']]], 401);
        }else if($userOtp && $now->isAfter($userOtp->expire_at)){
            return response(['errors' => ['otp' => ['Your OTP has been expired']]], 401);
        }

        $user = User::whereId($request->user_id)->first();

        if ($user) {
            $userOtp->update([
                'expire_at' => now()
            ]);

            Auth::login($user);

            $token = auth()->user()->createToken('API Token')->accessToken;

            return response(['token' => $token]);
        }

        return response(['errors' => ['otp' => ['Your Otp is not correct']]], 401);
    }
}
