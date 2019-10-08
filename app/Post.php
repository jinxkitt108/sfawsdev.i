<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table Name
    protected $table = 'posts';
    // Fields
    protected $fillable = [
        'author_id', 'title', 'body', 'cover_image'
    ];
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
    protected $with = ['author:id,name,type'];

    public function author(){
        return $this->belongsTo(User::class);
    }

    // public function comments(){
    //     return $this->hasMany(Comment::class)->orderBy('updated_at', 'asc');
    // }
}
