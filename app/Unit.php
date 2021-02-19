<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name', 
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('id', 'price');
        // return $this->belongsToMany('App\Item')->withPivot('quantity', 'pric');
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
        // return $this->belongsToMany('App\Item')->withPivot('quantity', 'pric');
    }
}
