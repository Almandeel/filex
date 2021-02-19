<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    public const STATUS_AVAILABLE = 1;
    public const STATUS_UNAVAILABLE = 0;
    public const STATUSES = [
    self::STATUS_AVAILABLE => 'available',
    self::STATUS_UNAVAILABLE => 'unavailable',
    ];
    public const STATUSES_CLASSES = [
    self::STATUS_AVAILABLE => 'success',
    self::STATUS_UNAVAILABLE => 'warning',
    ];
    
    protected $table = "item_unit";
    protected $fillable = ['unit_id', 'item_id', 'price', 'status'];
    
    public function itemId(){
        return $this->item->id;
    }
    
    public function itemName(){
        return $this->item->name;
    }
    
    public function unitId(){
        return $this->unit->id;
    }
    
    public function unitName(){
        return $this->unit->name;
    }
    
    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }
    
    // public function getItemIdAttribute(){
    //     return $this->item->id;
    // }
    
    // public function getItemNameAttribute(){
    //     return $this->item->name;
    // }
    
    public function getNameAttribute(){
        return $this->item->name . '-' . $this->unit->name;
    }
    
    public function unit()
    {
        return $this->belongsTo('App\Unit', 'unit_id');
    }
    
    // public function getUnitIdAttribute(){
    //     return $this->unit->id;
    // }
    
    // public function getUnitNameAttribute(){
    //     return $this->unit->name;
    // }
    
    public function getStatusNameAttribute(){
        return $this->getStatus('name');
    }
    
    public function getStatusClassAttribute(){
        return $this->getStatus('class');
    }
    
    public function getStatusTitleAttribute(){
        return $this->getStatus('title');
    }
    
    public function getStatus($type = 'value'){
        if ($type == 'name') {
            return self::STATUSES[$this->status];
        }
        elseif ($type == 'class') {
            return self::STATUSES_CLASSES[$this->status];
        }
        elseif ($type == 'title') {
            return __('restaurant::items.statuses.' . self::STATUSES[$this->status]);
        }
        return $this->status;
    }
    
    public function displayStatus($format = 'none'){
        $status = __('restaurant::items.statuses.' . $this->getStatus('name'));
        if ($format == 'html') {
            $builder = '<span class="label label-' . $this->getStatus('class') . '">';
            $builder .= $status;
            $builder .= '</span>';
            
            return $builder;
        }
        return $status;
    }
    
    public function checkStatus($status){
        $status_type = gettype($status);
        if ($status_type == 'string') {
            return $this->getStatus('name') == $status;
        }
        
        elseif ($status_type == 'integer') {
            return $this->getStatus() == $status;
        }
        
        throw new Exception("Unsupported status type", 1);
    }
    
    public function isAvailable(){
        return $this->checkStatus('available');
    }
    
    public function isUnavailable(){
        return $this->checkStatus('unavailable');
    }
}