<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuthableModel;
class Expense extends Model
{
    use AuthableModel;
    protected $fillable = [
    'amount', 'details', 'entry_id', 'safe_id', 'bill_id', 'invoice_id', 'user_id'
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
        $entry = Entry::newExpense([
        'amount' => $attributes['amount'],
        'from_id' => Account::expenses()->id,
        'to_id' => $attributes['safe_id'],
        'details' => $attributes['details'],
        ]);
        $attributes['entry_id'] = $entry->id;
        $attributes['user_id'] = auth()->user()->id;
        $expense = static::query()->create($attributes);
        if($expense->bill) $expense->bill->refresh();
        if($expense->invoice) $expense->invoice->refresh();
        return $expense;
    }
    
    public function update(array $attributes = [], array $options = []){
        if(!isset($this['entry']->expense)){
            if(isset($this->entry)) {
                $this->entry->update(['amount' => $attributes['amount'], 'details' => $attributes['details']]);
            }
            
            $result = parent::update($attributes, $options);
            if($this->bill) $this->bill->refresh();
            if($this->invoice) $this->invoice->refresh();
            return $result;
        }
        return null;
    }
    public function resetBill(){
        $chargePerItem = $this->bill->items->count() ? $this->amount / $this->bill->items->count() : 0;
        foreach ($this->bill->items as $item) {
            $chargePerUnit = $chargePerItem ? $chargePerItem / $item->quantity : 0;
            $item->update([
            'expense' => $item->expense - $chargePerUnit,
            'expenses' => $item->expenses - $chargePerItem,
            ]);
        }
        $this->bill->refresh();
    }
    public function resetInvoice(){
        $chargePerItem = $this->invoice->items->count() ? $this->amount / $this->invoice->items->count() : 0;
        foreach ($this->invoice->items as $item) {
            $chargePerUnit = $chargePerItem ? $chargePerItem / $item->quantity : 0;
            $item->update([
            'expense' => $item->expense - $chargePerUnit,
            'expenses' => $item->expenses - $chargePerItem,
            ]);
        }
        $this->invoice->invoice->refresh();
    }
    public function delete(){
        $bill = $this->bill;
        $invoice = $this->invoice;
        if($bill){
            $this->resetBill();
        }
        else if($invoice){
            $this->resetInvoice();
        }
        if($this->entry) $this->entry->delete();
        $result = parent::delete();
        return $result;
    }
}