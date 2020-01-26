<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messageable extends Model
{
    protected $fillable = [
        'message_id', 'sender_id', 'receiver_id', 'read', 'sender_deleted', 'receiver_deleted'
    ];

    protected $with = ['receiver'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function receiver() {
        return $this->belongsTo(User::class);
    }

}
