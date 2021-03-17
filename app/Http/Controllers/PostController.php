<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with("user")->get();
        return response(['status' => true,'posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['user_id'] = Auth::id();

        $post = new Post();

        if($post->create($request->all())){
            return response(['status' => true,'message' => "Successfuly has been posted your post"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        
        return response(['status' => true,'post' => $post,'user' => $post->user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    /**
     * Upload video
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required',
        ]);

        $videoName = time().'.'.request()->video->getClientOriginalExtension();

        request()->video->move(storage_path("video/"), $videoName);

        return response(['status'=> true,'message' => "storage/video/".$videoName]);
    }

    /**
     * get spicifig user profile and posts
     * @param id
     */
    public function getUserPost($id)
    {
        $posts = Post::where("user_id",$id)->get();

        $user = User::find($id);
        
        return response(['status' => true,'posts' => $posts,'user' => $user]);
    }
}
