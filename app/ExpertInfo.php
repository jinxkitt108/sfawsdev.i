<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertInfo extends Model
{
    protected $fillable = [
        'user_id',
        'website',

        //Experties
        'categories',
        'jobs',

        //Career
        'work',
        'company_name',
        'position',
        'work_description',

        //Achievements
        'school',
        'degree',
        'trainings',
        'attachments'
    ];

    protected $casts =[
        'categories' => 'array',
        'jobs' => 'array',
        'trainings' => 'array',
        'attachments' => 'array'
    ];
}
