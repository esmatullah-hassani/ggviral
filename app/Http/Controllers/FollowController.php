<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with("user")
        ->whereIn('user_id', function ($query) {
            return $query->select('user_2')->from('follows')->where('user_1', Auth::id());
        })
        ->orderBy("created_at",'desc')->where("status",1)->paginate(6);
       
        return response(['status' => true,'posts' => $posts]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pstFollower()
    {
        $posts = Post::with("user")
        ->whereIn('user_id', function ($query) {
            return $query->select('user_1')->from('follows')->where('user_2', Auth::id());
        })
        ->orderBy("created_at",'desc')->where("status",1)->paginate(6);
       
        return response(['status' => true,'posts' => $posts]);
    }

    /**
     * store follow
     * @param request
     * 
     */
    public function store(Request $request)
    {
        $follows = Follow::where("user_1",$request->user_1)->where("user_2",$request->user_2)->get();
        if($follows->isEMpty()){
            $follow = new Follow();
            if($follow->create($request->all())){
                return response()->json(['status' => true,'message' =>1]);
            }
        }
        else{
            $follows[0]->delete();
            return response()->json(['status' => true,'message' => 2]);

        }
    }
}
