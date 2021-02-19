<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStoreUnit extends Model
{
    protected $table = "item_store_unit";
    protected $fillable = [
    'item_store_id',
    'item_unit_id',
    'quantity',
    'price_purchase',
    'price_sell',
    ];
    
    public function itemId(){
        return $this->itemStore->item;
    }

    public function item(){
        return $this->itemStore->item;
    }
    
    public function itemName(){
        return $this->itemStore->itemName();
    }
    
    public function storeId(){
        return $this->itemStore->storeId();
    }
    
    public function storeName(){
        return $this->itemStore->storeName();
    }
    
    public function unitId(){
        return $this->itemUnit->id;
    }
    
    public function unitName(){
        return $this->itemUnit->unitName();
    }
    
    public function itemStore()
    {
        return $this->belongsTo('App\ItemStore', 'item_store_id');
    }
    
    public function itemUnit()
    {
        return $this->belongsTo('App\ItemUnit', 'item_unit_id');
    }
    
    public function billsItems()
    {
        return $this->hasMany('App\BillItem', 'item_store_unit_id');
    }
}