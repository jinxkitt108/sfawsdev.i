<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'bio', 'photo', 'theme'
    ];

    protected $casts = [
        'theme' => 'boolean'
    ];

    //User Method belongs to a User
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
