<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        if (isset($validated['avatar'])) {
            $validated['avatar'] = ImgController::uploadImg(ImgController::AVATARS, $validated['login'], $validated['avatar']);
        } else {
            $validated['avatar'] = ImgController::getDefaultAvatarPath()[0];
        }

        User::create($validated);

        return $this->sendSuccess(['msg' => 'Successful register'], 201);
    }

    public function authenticate(AuthRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {;
            $token = $request->user()->generateBearerToken();
            return $this->sendSuccess(['token' => $token]);
        }

        return $this->sendError(['msg' => 'Incorrect login or password']);
    }
}
