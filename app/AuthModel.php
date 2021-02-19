<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthModel extends Model
{
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public static function create(array $attributes = [])
    {
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        return $model;
    }
}
?>