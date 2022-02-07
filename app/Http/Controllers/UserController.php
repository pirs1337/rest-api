<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserById($id){
        $user = User::find($id);

        if ($user) {
            return new UserResource($user);
        }

        return $this->sendError(['msg' => 'User Not Found'], 404);

    }
}
