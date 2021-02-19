<?php
namespace App\Traits;
use App\Account;
/**
*  Accountable Trait
*/
trait Accountable
{
    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }
    
    
    public static function bootAccountable()
    {
        static::deleting(function($model){
            if(!is_null($model->account)) $model->account->delete();
        });
    }
    
}