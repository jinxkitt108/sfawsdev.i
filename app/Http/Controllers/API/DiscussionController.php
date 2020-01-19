<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Discussion;
use App\Reply;

class DiscussionController extends Controller
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
        $discussions = Discussion::latest()->paginate(10);
        return $discussions;
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
            'category' => 'required',
            'content' => 'required',
            'photo' => 'nullable'
        ]);

        if ($request->photo) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $dir = 'storage/discussion_photo/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            \Image::make($request->photo)->save(public_path('storage/discussion_photo/') . $name);
        } else {
            $name = null;
        }

        $discussion = Discussion::create([
            'user_id' => auth('api')->user()->id,
            'title' => $request['title'],
            'category' => $request['category'],
            'content' => $request['content'],
            'photo' => $name,
        ]);

        return $discussion;
    }

    public function postReply(Request $request)
    {
        $this->validate($request, [
            'discussion_id' => 'required',
            'to_user_id' => 'nullable',
            'content' => 'required',
            'photo' => 'nullable'
        ]);

        if ($request->photo) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $dir = 'storage/reply_photo/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            \Image::make($request->photo)->save(public_path('storage/reply_photo/') . $name);
        } else {
            $name = null;
        }

        $reply = Reply::create([
            'author_id' => auth('api')->user()->id,
            'discussion_id' => $request['discussion_id'],
            'to_user_id' => $request['to_user_id'],
            'content' => $request['content'],
            'photo' => $name,
        ]);

        return $reply;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discussion = Discussion::findOrFail($id);
        return $discussion;
    }

    public function fetchAllReply($id)
    {
        $replies = Reply::where('discussion_id', $id)->orderBy('created_at', 'ASC')->get();
        $discussion = Discussion::findOrFail($id);
        $auth_id = auth('api')->user()->id;
        foreach ($replies as $reply) {
            if ($discussion->user_id === $auth_id) {
                $reply['authorize'] = true;
            } else {
                $reply['authorize'] = false;
            }
        }

        return $replies;
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

    public function toggleBestAnswer(Request $request)
    {
        $best_answer = Reply::where('discussion_id', $request['discussion_id'])->where('best_answer', true);
        $reply = Reply::where('id', $request['id']);

        if($best_answer){
            $best_answer->update(['best_answer' => false]);
        }
        if ($request['best_answer']) {
            $reply->update(['best_answer' => false]);
            dd('Best answer updated!');
        } else {
            $reply->update(['best_answer' => true]);
            dd('Best answer updated!');
        }
    }

    public function toggleResolved(Request $request)
    {     
        $discussion = Discussion::where('id', $request['id']);

        if ($request['resolved']) {
            $discussion->update(['resolved' => false]);
            dd('resolved status updated!');
        } else {
            $discussion->update(['resolved' => true]);
            dd('resolved status updated!');
        }
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
