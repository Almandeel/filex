<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $table = "bill_item";
    protected $fillable = [
    'item_store_unit_id',
    'bill_id',
    'bill_item_id',
    'item_id',
    'unit_id',
    'quantity',
    'expense',
    'expenses',
    'price',
    ];
    
    public function items(){
        return $this->hasMany('App\BillItem', 'bill_item_id');
    }
    
    public function item(){
        return $this->belongsTo('App\Item', 'item_id');
    }
    
    public function unit(){
        return $this->belongsTo('App\Unit', 'unit_id');
    }
    
    public function itemName(){
        if($this->itemStoreUnit){
            return $this->itemStoreUnit->itemName();
        }
        return $this->item['name'];
    }
    
    public function unitName(){
        if($this->itemStoreUnit){
            return $this->itemStoreUnit->unitName();
        }
        return $this->unit['name'];
    }
    
    public function itemId(){
        return $this->itemStoreUnit->itemId();
    }
    
    public function unitId(){
        return $this->itemStoreUnit->unitId();
    }
    
    public function storeId(){
        return $this->itemStoreUnit->storeId();
    }
    
    public function storeName(){
        return $this->itemStoreUnit->storeName();
    }
    
    public function billId(){
        return $this->bill->id;
    }
    
    public function totalPrice(){
        $expenses = 0;
        foreach ($this->items as $item) {
            $expenses += $item->expense * $item->quantity;
        }
        return $this->price + $this->items->sum('expense');
        
    }
    
    public function amount(){
        // return ($this->quantity * $this->price) + $this->items->sum('expenses');
        $receivedAmount = 0;
        foreach ($this->items as $item) {
            $receivedAmount += ($this->price + $item->expense) * $item->quantity;
        }
        $remainAmount = ($this->quantity - $this->items->sum('quantity')) * $this->price;
        return $receivedAmount + $remainAmount;
    }
    
    public function itemStoreUnit()
    {
        return $this->belongsTo('App\ItemStoreUnit', 'item_store_unit_id');
    }
    
    public function bill()
    {
        return $this->belongsTo('App\Bil', 'bill_id');
    }
    
    public function billItem(){
        return $this->belongsTo('App\BillItem', 'bill_item_id');
    }
    
    public function delete(){
        // if($this->billItem){
        //     if($this->billItem->itemStoreUnit){
        //         $this->billItem->itemStoreUnit->update(['quantity' => $this->itemStoreUnit->quantity - $this->quantity]);
        //     }
        // }else{
        //     if($this->itemStoreUnit){
        //         $this->itemStoreUnit->update(['quantity' => $this->itemStoreUnit->quantity - $this->quantity]);
        //         $this->items()->delete();
        //     }
        // }
        
        if(!$this->billItem){
            if($this->itemStoreUnit){
                $this->itemStoreUnit->update(['quantity' => $this->itemStoreUnit->quantity - $this->items->sum('quantity')]);
            }
            
            foreach ($this->items as $item) {
                $item->delete();
            }
        }
        
        return parent::delete();
    }
}