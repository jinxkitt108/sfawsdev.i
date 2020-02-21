<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Chat;
use App\User;

class ChatController extends Controller
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
            'user' => 'required',
            'body' => 'required',
        ]);

        $auth_user = auth('api')->user();
        $this_user = User::find($request->user['id']);
        $participants = [$auth_user, $this_user];
        $content = $request['body'];

        $has_conversation = Chat::conversations()->between($auth_user, $this_user);

        if ($has_conversation) {
            $message = Chat::message($content)
                ->from($auth_user)
                ->to($has_conversation)
                ->send();
        } else {
            $conversation = Chat::createConversation($participants)->makeDirect();
            $message = Chat::message($content)
                ->from($auth_user)
                ->to($conversation)
                ->send();
        }

        $message['is_sender'] = 0;

        return $message;
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

    public function getConvo($id)
    {

        $auth_user = auth('api')->user();
        $this_user = User::find($id);

        $conversation = Chat::conversations()->between($auth_user, $this_user);
        if ($conversation) {
            $messages = Chat::conversation($conversation)->setParticipant($this_user)->getMessages();
            if ($messages) {
                foreach ($messages as $key => $message) {
                    Chat::message($message)->setParticipant($auth_user)->markRead();
                }
            }
            return $messages;
        } else {
            return $conversation;
        }
    }

    public function getAllConvo()
    {
        $auth_user = auth('api')->user();
        $messages = Chat::conversations()->setParticipant($auth_user)->limit(25)->page(1)->get();

        return $messages;
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
