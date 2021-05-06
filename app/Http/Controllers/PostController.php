<?php

namespace App\Http\Controllers;

use App\Models\Follow;
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
        $follows = Follow::where('user_1',Auth::id())->get();
        $posts = Post::with("user")
       
        ->orderBy("created_at",'desc')->where("status",1)->paginate(6);
        return response(['status' => true,'posts' => $posts,'follows' => $follows]);
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
    public function destroy($id)
    {
        if(Post::find($id)->delete()){
            return response(['status' => true, 'message' => 'Successfully delete']);
        }
        else{
            return response(['status' => false,'message' => "Oops has ben problem"]);
        }
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

        request()->video->move("video/", $videoName);

        return response(['status'=> true,'message' => "video/".$videoName]);
    }

    /**
     * get spicifig user profile and posts
     * @param id
     */
    public function getUserPost(Request $request)
    {
        $posts = Post::where("user_id",$request->user_id)->orderBy("created_at",'desc')->paginate(6);

        $user = User::find($request->user_id);
        $following = count(User::select('follows.user_1')->leftJoin('follows','users.id','follows.user_1')->where('follows.user_1',auth()->id())->groupBy('user_1')->get());
        $follower = count(User::select('follows.user_2')->leftJoin('follows','users.id','follows.user_2')->where('follows.user_2',auth()->id())->groupBy('user_2')->get());
        return response(['status' => true,'posts' => $posts,'user' => $user,'follower' => $follower,'following' => $following]);
    }

    /**
     * hide post from another person
     * @param id
     */
    public function hidePost($id)
    {
        $post = Post::find($id);
        $post->status = 0;
        if($post->save()){
            return response(['status' => true, 'message' => "Successfully hide your post from another persone"]);
        }
        else{
            return response(['status' => false,'message' => "Oops problem"]);
        }
    }

    /**
     * search post
     */
    public function searchPost(Request $request){
        $follows = Follow::where('user_1',Auth::id())->get();
        $posts = Post::with("user")->where("discription",'like','%'.$request->discription."%")
       
        ->orderBy("created_at",'desc')->where("status",1)->paginate(6);
        return response(['status' => true,'posts' => $posts,'follows' => $follows]);
    }

}
