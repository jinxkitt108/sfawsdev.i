<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id', 'subject', 'content', 'attachment'
    ];

    protected $casts = [
        'receiver' => 'array',
    ];

    protected $with = ['sender'];

    public function sender(){
        return $this->belongsTo(User::class);
    }

    public function messageables(){
        return $this->hasMany(Messageable::class, 'message_id');
    }
   
}
