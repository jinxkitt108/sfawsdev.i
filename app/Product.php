<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id', 'name', 'category', 'unit', 'stocks', 'price', 'description', 'photos', 'public'
    ];

    protected $casts =[
        'photos' => 'array',
    ];

    protected $with = ['store:id,name,photo'];

    public function getPriceAttribute($price) {
        return round($price, 2);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
