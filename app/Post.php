<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'author_id', 'title', 'content', 'cover_image'
    ];

    // User Relationship
    public function user(){
        return $this->belongsTo(User::class);
    }
}
