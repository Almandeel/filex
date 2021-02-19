<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = "invoice_item";
    protected $fillable = [
    'item_store_unit_id',
    'invoice_id',
    'invoice_item_id',
    'bill_item_id',
    'item_id',
    'unit_id',
    'quantity',
    'expenses',
    'price_purchase',
    'price_sell',
    ];
    
    public function items(){
        return $this->hasMany('App\InvoiceItem', 'invoice_item_id');
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
        return $this->itemStoreUnit ? $this->itemStoreUnit->storeName() : 'بيع مباشر';
    }
    
    public function invoiceId(){
        return $this->invoice->id;
    }
    
    public function totalPrice(){
        return $this->price_sell + $this->items->sum('expenses');
    }
    
    public function amount(){
        return ($this->quantity * $this->price_sell) + $this->items->sum('expenses');
    }
    
    public function itemStoreUnit()
    {
        return $this->belongsTo('App\ItemStoreUnit', 'item_store_unit_id');
    }
    
    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }
    public function invoiceItem(){
        return $this->belongsTo('App\InvoiceItem', 'invoice_item_id');
    }
    public function billItem(){
        return $this->belongsTo('App\BillItem', 'bill_item_id');
    }
    
    public function isProfit():bool{
        return $this->price_sell > $this->price_purchase;
    }
    
    public static function create(array $attributes = [])
    {
        $model = $model = static::query()->create($attributes);
        if($model->invoiceItem) {
            if($model->invoiceItem->itemStoreUnit){
                $model->invoiceItem->itemStoreUnit->update(['quantity' => $model->invoiceItem->itemStoreUnit->quantity - $model->quantity]);
            }
        }
        // else{
        //     $model->update([
        //     'price_purchase' => $model->itemStoreUnit->price_purchase,
        //     'price_sell' => $model->itemStoreUnit->price_sell
        //     ]);
        // }
        return $model;
    }
    
    public function delete(){
        if(!$this->invoiceItem){
            foreach ($this->items as $item) {
                $item->delete();
            }
        }
        
        if($this->invoiceItem) {
            if($this->invoiceItem->itemStoreUnit){
                $this->invoiceItem->itemStoreUnit->update(['quantity' => $this->invoiceItem->itemStoreUnit->quantity + $this->quantity]);
            }
        }
        return parent::delete();
    }
}