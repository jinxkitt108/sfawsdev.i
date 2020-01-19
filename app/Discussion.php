<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    // Fields
    protected $fillable = [
        'user_id', 'title', 'category', 'content', 'photo'
    ];

    protected $with = ['user:id,name,type', 'replies:discussion_id,content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'discussion_id');
    }
}
