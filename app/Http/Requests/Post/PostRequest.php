<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\ApiRequest;

class PostRequest extends ApiRequest
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
            'title' => 'required|max:255|string|not_in:public,posts,post',
            'text' =>  'required|string',
            'img' => 'image',
        ];
    }
}
