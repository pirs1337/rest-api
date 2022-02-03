<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    const DIR = 'posts/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendSuccess(['posts' => PostResource::collection(Post::all())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $this->validatePost($request);
        $user = User::getUserByBearerToken($request);

        if ($user) {
            $data = array_merge(['user_id' => $user->id], $validated);

            if(isset($data['img'])){
                $data['img'] = ImgController::uploadImg(self::DIR, $data['title'], $data['img']);
            }

            $post = Post::create($data);
            return $this->sendSuccess(['status' => true, 'data' => new PostResource($post)]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if ($post) {
           return $this->sendSuccess(['post' => new PostResource($post)]);
        }

        return $this->sendError(['msg' => 'Not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function validatePost($request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string',
            'text' =>  'required|string',
            'img' => 'image',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator);
        }

        $validated = $validator->validated();

        return $validated;
    }
}
