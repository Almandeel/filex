<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuthableModel;

class Payment extends Model
{
    use AuthableModel;
    public const TYPE_DEBT = -1;
    public const TYPE_CREDIT = 1;
    
    protected $fillable = [
    'id', 'amount', 'details', 'entry_id', 'user_id', 'bill_id', 'invoice_id', 'safe_id'
    ];
    
    public function entry(){
        return $this->belongsTo('App\Entry');
    }
    
    public function safe(){
        return $this->belongsTo('App\Safe');
    }
    
    public function bill(){
        return $this->belongsTo('App\Bill');
    }
    
    public function invoice(){
        return $this->belongsTo('App\Invoice');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public static function create(array $attributes = [])
    {
        $entry = Entry::newPayment([
            'amount' => $attributes['amount'],
            'from_id' => $attributes['from_id'],
            'to_id' => $attributes['to_id'],
            'details' => $attributes['details'],
        ]);
        
        
        $attributes['entry_id'] = $entry->id;
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        if($model->bill) $model->bill->refresh();
        if($model->invoice) $model->invoice->refresh();
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        $this->entry->update(['amount' => $attributes['amount'], 'details' => $attributes['details']]);
        $result = parent::update($attributes, $options);
        
        if($this->bill) $this->bill->refresh();
        if($this->invoice) $this->invoice->refresh();
        
        return $result;
    }
    
    public function delete(){
        $bill = $this->bill;
        $invoice = $this->invoice;
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if($this->entry) $this->entry->delete();
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        if($bill) $bill->refresh();
        if($invoice) $invoice->refresh();
        return $result;
    }
    
    
}