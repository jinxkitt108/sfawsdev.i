<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Comment extends Model
{
    use CanBeLiked;
    //Fields
    protected $fillable = [
        'post_id', 'author_id', 'content'
    ];

    protected $with = ['author:id,name,type'];

    public function author(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
