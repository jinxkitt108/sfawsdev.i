<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;
use App\Comment;

class FollowController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        $followingUsers = $user->followings()->orderBy('name', 'ASC')->get();
        return $followingUsers;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Toggle Follow
        $user = User::find($request->user_id);
        $follow= auth('api')->user()->toggleFollow($user);
        return $follow;
    }

    public function commend(Request $request)
    {
        //Toggle Commend
        $post = Post::find($request->post_id);
        $commend= auth('api')->user()->toggleLike($post);
        return $commend;
    }

    public function agree(Request $request)
    {
        //Toggle Agree
        $comment = Comment::find($request->comment_id);
        $agree= auth('api')->user()->toggleLike($comment);
        return $agree;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
}
