<?php

use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LoginWithSocialiteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(auth()->check()){
    $users = User::where('id', '<>', Auth::id())->with(["following" => function($q){
        $q->where("user_1",Auth::id());
    }])->orderBy('last_seen','desc')->get();
    foreach($users as $user)
        return view("index",['users' => $users]);
    }
    return view('auth.login');
});

Route::post("/resend-email/{email}/{name}/{id}",[UserController::class,'sendEmail']);
Route::post("/register-user",[UserController::class,'registerUser'])->name("registeruser");
Route::get("/verify-user-email/{email}/{id}",[UserController::class,"registerVerifyUser"]);
//Route socialate
Route::get('authorized/google', [LoginWithSocialiteController::class,'redirectToGoogle']);
Route::get('authorized/google/callback', [LoginWithSocialiteController::class,'handleGoogleCallback']);
Route::get('/authorized/facebook', [LoginWithSocialiteController::class,'redirectToFacebook']);
Route::get('/authorized/facebook/callback', [LoginWithSocialiteController::class,'handleFacebookCallback']);
Route::get("/logout",[UserController::class,'logoutUser']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $users = User::where('id', '<>', Auth::id())->with(["following" => function($q){
        $q->where("follows.user_1",Auth::id());
    }])->orderBy('last_seen','desc')->get();
    return view('index',['users' => $users]);
    Route::get("/users-get",function(){
        return response()->json(['message' => "Hello dear"]);
    });
    
})->name('dashboard');

// Endpoints to call or receive calls.
Route::post('/video/call-user', [ChatController::class,'callUser']);
Route::post('/video/accept-call', [ChatController::class,'acceptCall']);

Route::post("/upload",[PostController::class,'uploadVideo']);

Route::post("/posts",[PostController::class,'store']);

Route::get("/posts",[PostController::class,'index']);

Route::post("/user-posts",[PostController::class,"getUserPost"]);

Route::get("/video-detail/{id}",[PostController::class,'show']);

//Comment route
Route::post("/comments",[CommentController::class,'store']);
Route::get("/comments/{id}",[CommentController::class,"show"]);

Route::delete("/posts/{id}/destroy",[PostController::class,'destroy']);

Route::delete("/posts/{id}/hide",[PostController::class,'hidePost']);

Route::post("/follows",[FollowController::class,'store']);

Route::get('/posts-following',[FollowController::class,'index']);

Route::get('/posts-follower',[FollowController::class,'postFollower']);

Route::post("/search-post",[PostController::class,"searchPost"]);
Route::post("/edit-image/{id}",[UserController::class,'editImage']);

//
Route::post("/check-coin",[CoinController::class,'checkCoin']);

Route::post("/bye-coin",[CoinController::class,'store']);

Route::post("/set-gift",[CoinController::class,'setGift']);
