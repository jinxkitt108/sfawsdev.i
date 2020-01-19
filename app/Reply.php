<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'author_id', 'discussion_id', 'to_user_id', 'content', 'photo', 'best_answer'
    ];

    protected $with = ['author:id,name,type'];

    public function author(){
        return $this->belongsTo(User::class);
    }

    public function discussion(){
        return $this->belongsTo(Discussion::class, 'discussion_id');
    }

}
