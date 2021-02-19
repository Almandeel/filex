<?php
namespace App\Traits;
/**
* AuthableModel
*/
trait AuthableModel
{
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public static function bootAuthableModel(){
        static::creating(function($model) {
            if (is_null($model->user_id)) {
                $model->user_id = auth()->user()->getKey();
            }
        });
    }
}