<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStore extends Model
{
    protected $table = "item_store";
    protected $fillable = [
    'store_id',
    'item_id',
    ];
    
    public function itemId(){
        return $this->item->id;
    }
    
    public function itemName(){
        return $this->item->name;
    }
    
    public function storeId(){
        return $this->store->id;
    }
    
    public function storeName(){
        return $this->store->name;
    }
    
    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }
    
    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }
    
    public function items()
    {
        return $this->hasMany('App\ItemStoreUnit', 'item_store_id');
    }
}