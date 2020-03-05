<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'user_id', 'day', 'from', 'to'
    ];
}
