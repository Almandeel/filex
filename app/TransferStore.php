<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferStore extends Model
{
    protected $fillable = ['from_store', 'to_store', 'user_id'];

    public function fromStore() {
        return $this->belongsTo('App\Store', 'from_store');
    }

    public function toStore() {
        return $this->belongsTo('App\Store', 'to_store');
    }

    public function items() {
        return $this->hasMany('App\TransferStoreItem');
    }
}
