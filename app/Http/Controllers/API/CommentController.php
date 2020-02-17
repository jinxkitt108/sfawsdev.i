<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewComment;
use App\Comment;
use App\Post;
use App\User;

class CommentController extends Controller
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
        $this->validate($request, [
            'body' => 'required'
        ]);

        $post_author = User::find($request['post_author']);
        $post = Post::find($request['post_id']);

        $comment = Comment::create([
            'author_id' => auth('api')->user()->id,
            'post_id' =>  $request['post_id'],
            'content' => $request['body']
        ]);

        if ($request['post_author'] !== auth('api')->user()->id) {
            $data = [
                'type' => 'comment',
                'data' => $post,
                'sender' => auth('api')->user(),
                'notification' => 'commented on your Ads "' . $post->title . '"',
            ];
            $post_author->notify(new NewComment($data));
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
        $comment = Comment::findOrFail($id);

        //Delete the post
        $comment->delete();
        return ['message' => 'Comment Deleted'];
    }
}
