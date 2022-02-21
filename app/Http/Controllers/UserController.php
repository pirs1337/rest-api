<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserById($id){
        $user = User::find($id);

        if ($user) {
            return  $this->sendSuccess(['data' => new UserResource($user)]);
        }

        return $this->userNotFound();

    }

    public function getAuthUser(){

        $user = Auth::user();
        if ($user) {
            return  $this->sendSuccess(['data' => new UserResource($user)]);
        }

        return $this->userNotFound();
    }

    public function getUserPosts($id){
        $user = User::find($id);

        if ($user) {
            $posts = $user->posts;
            if ($posts) {
                return $this->sendSuccess(['data' => PostResource::collection($posts)]);
            }
            
            return $this->sendError(['msg' => 'Posts not found']);
        }

        return $this->userNotFound();
    }

    private function userNotFound(){
        return $this->sendError(['msg' => 'User Not Found'], 404);
    }
}
