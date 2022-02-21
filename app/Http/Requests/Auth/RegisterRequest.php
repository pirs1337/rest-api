<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required|unique:users|alpha_dash|max:255|string',
            'email' => 'required|email|max:255|string',
            'password' =>  'required|max:255|alpha_dash|confirmed|string',
            'avatar' => 'image'
        ];
    }
}
