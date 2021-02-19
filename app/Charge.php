<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'amount', 'details', 'entry_id', 'bill_id'
    ];

    public function entry(){
        return $this->belongsTo('App\Entry');
    }

    public function bill(){
        return $this->belongsTo('App\Bill');
    }

    public static function add($bill, $account, $amount, $details){
        $entry = Account::purchasesCharges()->deposite($amount, $account->id, $details);
        return static::create([
            'amount' => $amount,
            'details' => $details,
            'entry_id' => $entry->id,
            'bill_id' => $bill->id,
        ]);
    }

    public function delete(){
        $receivedCount = $this->bill->items;
        $chargeAmount = $this->amount;
        $chargePerItem = $receivedCount->count() ? $chargeAmount / $receivedCount->count() : 0;
        foreach ($this->bill->items as $item) {
            $item->update(['expenses' => $item->expenses - $chargePerItem]);
        }
        if($this->entry) $this->entry->delete();
        return parent::delete();
    }
}
