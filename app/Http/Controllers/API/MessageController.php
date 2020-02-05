<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;
use App\Messageable;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = auth('api')->user()->sentMessages;
        foreach ($messages as $key => $item) {
            $item->messageables;
        }

        return $messages;
    }

    // INBOX MESSAGES
    public function fetchInbox()
    {
        $messageables = auth('api')->user()->messageables;
        foreach ($messageables as $key => $item) {
            $item->message;
        }
        return $messageables;
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
            'receiver' => 'required',
            'subject' => 'nullable',
            'content' => 'required',
            'attachment' => 'nullable'
        ]);

        // $receiver = [];
        // foreach ($request['receiver'] as $key => $item) {
        //     $user = new \stdClass();
        //     $user->id = $item['id'];
        //     array_push($receiver, $user);
        // }

        $receivers = $request['receiver'];

        $message = Message::create([
            'sender_id' => auth('api')->user()->id,
            'subject' => $request['subject'],
            'content' => $request['content'],
            'attachment' => null
        ]);

        foreach ($receivers as $key => $item) {
            Messageable::create([
                'message_id' => $message->id,
                'sender_id' =>     $message->sender_id,
                'receiver_id' => $item['id']
            ]);
        }
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
        $message = Message::findOrFail($id);
        return $message;
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

    public function deleteInbox(Request $request)
    {
        $auth_user = auth('api')->user();
        $message = Messageable::where('message_id', $request['id'])->where('receiver_id', $auth_user->id);
        $message->update(['receiver_deleted' => true]);
        dd('Message was removed!');
    }
}
