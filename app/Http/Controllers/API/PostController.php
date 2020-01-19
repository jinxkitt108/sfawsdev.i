<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;

class PostController extends Controller
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
        $userIds = $user->followings()->pluck('id')->toArray();
        $posts = Post::whereIn('author_id', $userIds)->orWhere('author_id', $user->id)->orderBy('created_at', 'DESC')->get();
        foreach ($posts as $post) {
            if ($post->author_id === $user->id) {
                $post['authorize'] = true;
            } else {
                $post['authorize'] = false;
            }
            if ($user->hasLiked($post)) {
                $post['commend'] = true;
            }
            $commends = $post->likers()->count();
            $post['commends'] = $commends;
            $comments = $post->comments;
            foreach ($comments as $comment) {
                if ($comment->author_id === $user->id) {
                    $comment['authorize'] = true;
                } else {
                    $comment['authorize'] = false;
                }
                if ($user->hasLiked($comment)) {
                    $comment['agree'] = true;
                }
            }
        }
        return $posts;
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
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'nullable'
        ]);

        if ($request->cover_image) {
            $name = time() . '.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];
            $dir = 'storage/cover_photo/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            \Image::make($request->cover_image)->save(public_path('storage/cover_photo/') . $name);
        } else {
            $name = null;
        }

        $post = Post::create([
            'author_id' => auth('api')->user()->id,
            'title' => $request['title'],
            'content' => $request['content'],
            'cover_image' => $name,
        ]);
        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth('api')->user();
        $posts = auth('api')->user()->posts;
        foreach ($posts as $post) {
            $post['authorize'] = true;
            if ($user->hasLiked($post)) {
                $post['commend'] = true;
            }
            $commends = $post->likers()->count();
            $post['commends'] = $commends;
            $comments = $post->comments;
            foreach ($comments as $comment) {
                if ($comment->author_id === $user->id) {
                    $comment['authorize'] = true;
                } else {
                    $comment['authorize'] = false;
                }
                if ($user->hasLiked($comment)) {
                    $comment['agree'] = true;
                }
            }
        }
        return $posts;
    }

    public function getPost($id)
    {

        $user = auth('api')->user();
        $posts = Post::where('author_id', $id)->orderBy('created_at', 'DESC')->get();
        foreach ($posts as $post) {
            if ($post->author_id === $user->id) {
                $post['authorize'] = true;
            } else {
                $post['authorize'] = false;
            }
            if ($user->hasLiked($post)) {
                $post['commend'] = true;
            }
            $commends = $post->likers()->count();
            $post['commends'] = $commends;
            $comments = $post->comments;
            foreach ($comments as $comment) {
                if ($comment->author_id === $user->id) {
                    $comment['authorize'] = true;
                } else {
                    $comment['authorize'] = false;
                }
                if ($user->hasLiked($comment)) {
                    $comment['agree'] = true;
                }
            }
        }
        return $posts;
    }

    public function singlePost($id)
    {
        $current_user = auth('api')->user();
        $post = Post::findOrFail($id);
        if ($post->author_id === $current_user->id) {
            $post['authorize'] = true;
        } else {
            $post['authorize'] = false;
        }
        if ($current_user->hasLiked($post)) {
            $post['commend'] = true;
        }
        $commends = $post->likers()->count();
        $post['commends'] = $commends;
        $comments = $post->comments;
        foreach ($comments as $comment) {
            if ($comment->author_id === $current_user->id) {
                $comment['authorize'] = true;
            } else {
                $comment['authorize'] = false;
            }
            if ($current_user->hasLiked($comment)) {
                $comment['agree'] = true;
            }
        }

        return $post;
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
        $post = Post::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $currentCover = $post->cover_image;
        if ($request->cover_image !=  $currentCover) {
            if ($request->cover_image != "") {
                // Upload new cover image
                $name = time() . '.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];

                \Image::make($request->cover_image)->save(public_path('storage/cover_photo/') . $name);
                $request->merge(['cover_image' => $name]);
            }
            $cover_image = public_path('storage/cover_photo/') . $currentCover;
            if (file_exists($cover_image)) {
                @unlink($cover_image);
            }
        }

        // Update Post
        $post->update($request->all());
        return ['message' => 'Info updated!'];
    }

    public function search()
    {
        $current_user = auth('api')->user();
        if ($search = \Request::get('q')) {
            $posts = Post::where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")
                    ->orwhere('content', 'LIKE', "%$search%");
            })->paginate(5);
            foreach($posts as $post) {
                $commends = $post->likers()->count();
                $post['commends'] = $commends;
            }
        }
        return $posts;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $comment = Comment::where('post_id', $id);
        $currentCover = $post->cover_image;
        $cover_image = public_path('storage/cover_photo/') . $currentCover;
        if ($currentCover != 'noimage.jpg') {
            @unlink($cover_image);
        } else { }
        //Delete the post
        $post->delete();
        $comment->delete();
        return ['message' => 'Post Deleted'];
    }
}
