<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreMailMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\User;
use App\Notifications\NewMessage;
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
    }

    // INBOX MESSAGES
    public function fetchInbox()
    {
        $messageables = auth('api')->user()->inbox;
        foreach ($messageables as $key => $item) {
            $item->message;
        }
        return $messageables;
    }

    // SENT MESSAGES
    public function fetchSent()
    {
        $auth_id = auth('api')->user()->id;
        $messages = Message::where('sender_id', $auth_id)->whereHas('messageables', function ($query) {
            $id = auth('api')->user()->id;
            $query->where('sender_id', $id)->where('sender_deleted', 0);
        })->latest()->get();
        foreach ($messages as $key => $message) {
            $message->messageables;
        }

        return $messages;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMailMessage $request)
    {
        if (!is_dir('storage/message_attachments/')) {
            mkdir('storage/message_attachments/');
        }

        /*    THis is a reference code for creating a new object and push into an array
        $receiver = [];
        foreach ($request['receiver'] as $key => $item) {
            $user = new \stdClass();
            $user->id = $item['id'];
            array_push($receiver, $user);
        } */

        $receivers = $request->receiver;
        $message = Message::create([
            'sender_id' => auth('api')->user()->id,
            'subject' => $request['subject'],
            'content' => $request['content'],
        ]);

        if ($message) {
            if ($request->attachments) {
                $files = $request->attachments;
                $attachments = [];
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $path = Storage::putFileAs('public/message_attachments/' . $message->id . '/', $file, $name);

                    // Saving Filename in the DB as reference
                    $item = new \stdClass();
                    $item->filename = $name;
                    array_push($attachments, $item);
                }
                $attachMessage = Message::findOrFail($message->id);
                $attachMessage->update(['attachment' =>  $attachments]);
            }

            if (is_array($receivers)) {
                foreach ($receivers as $key => $item) {
                    Messageable::create([
                        'message_id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $item
                    ]);
                }

                //Message Notification for multiple users
                $users = User::whereIn('id', $receivers)->get();
                $data = [
                    'type' => 'message',
                    'data' => $message,
                    'sender' => auth('api')->user(),
                    'notification' => 'New Message from ' . auth('api')->user()->name,
                ];
                Notification::send($users, new NewMessage($data));
            } else {
                Messageable::create([
                    'message_id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $request->receiver
                ]);

                //Message Notification
                $user = User::find($request->receiver);
                $data = [
                    'type' => 'message',
                    'data' => $message,
                    'sender' => auth('api')->user(),
                    'notification' => 'New Message from ' . auth('api')->user()->name,
                ];
                $user->notify(new NewMessage($data));
            }
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

    public function removeOneInbox(Request $request)
    {
        $message = Messageable::where('message_id', $request['id'])->where('receiver_id', auth('api')->user()->id);
        $message->update(['receiver_deleted' => true]);
    }

    public function deleteInbox(Request $request)
    {
        $messages = $request->json()->all();
        foreach ($messages as $key => $item) {
            $message = Messageable::where('id', $item['id']);
            $message->update(['receiver_deleted' => true]);
        }
        return $messages;
    }

    public function deleteSent(Request $request)
    {
        $messages = $request->json()->all();
        foreach ($messages as $key => $item) {
            $message = Messageable::where('message_id', $item['id']);
            $message->update(['sender_deleted' => true]);
        }
    }
}
