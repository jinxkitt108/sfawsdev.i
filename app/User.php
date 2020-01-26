<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanLike;
use Musonza\Chat\Traits\Messageable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanFollow, CanBeFollowed, CanLike, Messageable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'username', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['profile', 'store'];

    //Profile Method
    public function store()
    {
        return $this->hasOne(Store::class, 'owner_id');
    }

    //Profile Method
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    //Posts Method
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    //Comment Method
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    //Discussion Method
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'user_id');
    }
    
    //Messages Method
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messageables()
    {
        return $this->hasMany('App\Messageable', 'receiver_id');
    }



}
