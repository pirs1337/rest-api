<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){
        
        $validator = Validator::make($request->all(), [
            'login' => 'required|unique:users|alpha_dash|max:255|string',
            'email' => 'required|email|max:255|string',
            'password' =>  'required|max:255|alpha_dash|confirmed|string'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator);
        }

        // Retrieve the validated input...
        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return $this->sendSuccess(['msg' => 'Successful register'], 201);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' =>  'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator);
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        if (Auth::attempt($validated)) {;
            $token = $request->user()->generateBearerToken();
            return $this->sendSuccess(['token' => $token]);
        }

        return $this->sendError(['msg' => 'Incorrect login or password']);
    }
}
