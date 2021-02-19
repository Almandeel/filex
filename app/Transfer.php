<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use App\Traits\AuthableModel;
class Transfer extends Model
{
    use AuthableModel;
    protected $fillable = ['id', 'amount', 'details', 'from_id', 'to_id', 'entry_id', 'user_id'];
    
    public function reverse($details = null){
        $from_id = $this->to_id;
        $to_id = $this->from_id;
        $details = isset($details) ? $details : $this->details;
        return Transfer::create(['from_id' => $from_id, 'to_id' => $to_id, 'amount' => $this->amount, 'details' => $details]);
    }
    
    public function from(){
        $safe = $this->belongsTo('App\Safe', 'from_id');
        if($safe){
            return $safe;
        }
        
        return $this->belongsTo('App\Account', 'from_id');
    }
    
    public function fromAccount(){
        return $this->belongsTo('App\Account', 'from_id');
    }
    
    public function to(){
        $safe = $this->belongsTo('App\Safe', 'to_id');
        if($safe){
            return $safe;
        }else{
            return $this->belongsTo('App\Account', 'to_id');
        }
    }
    
    public function toAccount(){
        return $this->belongsTo('App\Account', 'to_id');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function entry(){
        return $this->belongsTo('App\Entry');
    }
    
    public static function create(array $attributes = [])
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if(array_key_exists('type', $attributes)){
            $entry = Entry::create($attributes);
        }else{
            $entry = Entry::newTransfer($attributes);
        }
        
        $attributes['entry_id'] = $entry->id;
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $model;
    }
    
    public function delete()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->entry->delete();
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
}