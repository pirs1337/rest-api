<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        
        $user = User::getUserByBearerToken($request);

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string|not_in:public,posts,post',
            'text' =>  'required|string',
            'img' => 'image',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator);
        }

        $validated = $validator->validated();

        if ($user) {
            $data = array_merge(['user_id' => $user->id], $validated);

            $post = Post::create($data);

            if(isset($data['img'])){
               $post->img = $data['img'] = ImgController::uploadImg(self::DIR, $data['title'].'-'.$post->id, $data['img']);
               $post->save();
            }

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
        $post = $this->getPost($id);

        if(!$post) {
            return $this->sendError(['msg' => 'Post not found'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string|not_in:public,posts,post',
            'text' =>  'required|string',
            'img' => 'image',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator);
        }

        $validated = $validator->validated();

        //update dir if new title 

        $post_id = '-'.$post->id;

        if ($post->title != $validated['title']) {
            if ($post->img && !isset($validated['img'])) {
            
                //change folder
                $newPath = str_replace($post->title, $validated['title'], $post->img);

                //move img and delete old dir
                ImgController::moveImg(self::DIR.$post->title.$post_id, self::DIR.$validated['title'].$post_id);
                ImgController::deleteDir(self::DIR.$post->title.$post_id);

                $post->img = $newPath;
            
            }
        }

        if (isset($validated['img'])) {
            if ($post->img) {
                //delete old dir
                ImgController::deleteDir(self::DIR.$post->title.'-'.$post->id);
            }
         
            $path = ImgController::uploadImg(self::DIR, $validated['title'].$post_id, $validated['img']);

            $post->img = $path;
            $post->save();
        }

        $post->title = $validated['title'];
        $post->text = $validated['text'];
        $post->save();

        return $this->sendSuccess(['msg' => 'Post updated']);
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

    public function getPost($id){
        $post = Post::find($id);

        if ($post) {
            return $post;
        } 

        return false;
    }
}
