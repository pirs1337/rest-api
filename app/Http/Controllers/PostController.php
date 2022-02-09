<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    const DIR = 'posts/';


    public function __construct()
    {
        $this->middleware('auth.by.bearer', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendSuccess(['data' => PostResource::collection(Post::all())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $user = User::getUserByBearerToken($request);

        $validated = $request->validated();

        if ($user) {
            $data = array_merge(['user_id' => $user->id], $validated);

            $post = Post::create($data);

            if (isset($data['img'])) {
               $post->img = $data['img'] = ImgController::uploadImg(self::DIR, $data['title'].'-'.$post->id, $data['img']);
               $post->save();
            }

            return $this->sendSuccess(['data' => new PostResource($post)]);
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
        $post = $this->getPost($id);

        if(!$post) {
            return $this->postNotFound();
        }

        return $this->sendSuccess(['data' => new PostResource($post)]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $post = $this->getPost($id);

        if(!$post) {
            return $this->postNotFound();
        }

        $user = User::thisUser($request, $post->user_id);

        if (!$user) {
            return $this->sendAccessDenied();
        }
    
        $validated = $request->validated();

        //update dir if new title 

        $post_id = '-'.$post->id;

        if ($post->title != $validated['title']) {
            if ($post->img && !isset($validated['img'])) {
            
                //change folder
                $newPath = str_replace($post->title, $validated['title'], $post->img);

                //move img and delete old dir
                $move = ImgController::moveImg(self::DIR.$post->title.$post_id, self::DIR.$validated['title'].$post_id);
                if (!$move) {
                    return $this->error();
                }
               
                $post->img = $newPath;
            }
        }

        if (isset($validated['img'])) {
            if ($post->img) {
                //delete old dir
                $deleteDir = ImgController::deleteDir(self::DIR.$post->title.$post_id);

                if (!$deleteDir) {
                    return $this->error();
                }
            }
         
            $path = ImgController::uploadImg(self::DIR, $validated['title'].$post_id, $validated['img']);

            if (!$path) {
                return $this->error();
            }

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
    public function destroy($id, Request $request)
    {
        $post = $this->getPost($id);

        if (!$post) {
            return $this->postNotFound();
        }

        $user = User::thisUser($request, $post->user_id);

        if (!$user) {
            return $this->sendAccessDenied();
        }

        if ($post->img) {
            $deleteDir = ImgController::deleteDir(self::DIR.$post->title.'-'.$post->id);

            if (!$deleteDir) {
                return $this->error();
            }
        }

        $post->delete();
        return $this->sendSuccess(['msg' => 'Post deleted']);
    }

    public function getPost($id){
        $post = Post::find($id);

        if ($post) {
            return $post;
        } 

        return false;
    }

    private function postNotFound(){
        return $this->sendError(['msg' => 'Post not found'], 404);
    }

    private function error(){
        return $this->sendError([
            'msg' => 'Unexpected error',
        ], 500);
    }
}
