<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Post extends Model
{
    use HasTags, CanBeLiked;
    // Table Name
    protected $table = 'posts';
    // Fields
    protected $fillable = [
        'author_id', 'title', 'content', 'cover_image'
    ];
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
    protected $with = ['author:id,name,type'];

    public function author(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
         return $this->hasMany(Comment::class)->orderBy('updated_at', 'asc');
   }
}
