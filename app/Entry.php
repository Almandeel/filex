<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuthableModel;
class Entry extends Model
{
    use AuthableModel;
    public const TYPE_JOURNAL   = 1;
    public const TYPE_ADJUST    = 2;
    public const TYPE_ADVERSE   = 3;
    public const TYPE_CLOSE     = 4;
    public const TYPE_DOUBLE    = 5;
    public const TYPE_USE       = 6;
    public const TYPE_OPEN     = 7;
    public const TYPE_OTHER     = 8;
    public const TYPE_PAYMENT   = 13;
    public const TYPE_EXPENSE   = 14;
    public const TYPE_INCOME    = 15;
    public const TYPE_TRANSFER    = 15;
    
    public const TYPES = [
    self::TYPE_JOURNAL,
    self::TYPE_ADJUST,
    self::TYPE_ADVERSE,
    self::TYPE_CLOSE,
    self::TYPE_DOUBLE,
    self::TYPE_USE,
    self::TYPE_OPEN,
    self::TYPE_OTHER,
    self::TYPE_PAYMENT,
    self::TYPE_EXPENSE,
    self::TYPE_INCOME,
    self::TYPE_TRANSFER,
    ];
    
    protected $table = 'entries';
    // protected $primaryKey = 'pk';
    protected $fillable = ['id', 'amount', 'type', 'from_id', 'to_id', 'details', 'bill_id', 'user_id', 'created_at', 'updated_at'];
    
    public function reverse($details = null){
        $from_id = $this->to_id;
        $to_id = $this->from_id;
        $details = isset($details) ? $details : $this->details;
        return Entry::create(['from_id' => $from_id, 'to_id' => $to_id, 'amount' => $this->amount, 'details' => $details]);
    }
    
    public function from(){
        return $this->belongsTo('App\Account', 'from_id');
    }
    
    public function to(){
        return $this->belongsTo('App\Account', 'to_id');
    }
    
    public function bills(){
        return $this->belongsToMany('App\Bill', 'bill_entry');
    }
    
    public function invoices(){
        return $this->belongsToMany('App\Invoice', 'entry_invoice');
    }
    
    public function salary(){
        return $this->hasOne('App\Salary', 'entry_id');
    }
    
    public function expense(){
        return $this->hasOne('App\Expense', 'entry_id');
    }
    
    public function payment(){
        return $this->hasOne('App\Payment', 'entry_id');
    }
    
    public function transfer(){
        return $this->hasOne('App\Transfer', 'entry_id');
    }
    
    public function cheque(){
        return $this->hasOne('App\Cheque', 'entry_id');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    public static function newPayment($data){
        return self::newEntry($data, ENTRY::TYPE_PAYMENT);
    }
    public static function newExpense($data){
        return self::newEntry($data, ENTRY::TYPE_EXPENSE);
    }
    public static function newIncome($data){
        return self::newEntry($data, ENTRY::TYPE_INCOME);
    }
    public static function newTransfer($data){
        return self::newEntry($data, ENTRY::TYPE_TRANSFER);
    }
    
    public static function newEntry($data, $type = ENTRY::TYPE_PAYMENT){
        $data['type'] = ENTRY::TYPE_PAYMENT;
        return self::create($data);
    }
    public static function create(array $attributes = [])
    {
        $attributes['user_id'] = auth()->user()->id;
        if(!array_key_exists('type', $attributes)){
            $attributes['type'] = self::TYPE_JOURNAL;
        }else{
            if (is_null($attributes['type'])) {
                $attributes['type'] = self::TYPE_JOURNAL;
            }
        }
        $model = static::query()->create($attributes);
        return $model;
    }
    public function update(array $attributes = [], array $options = []){
        if($this->payment) $this->payment->update(['amount' => $attributes['amount']]);
        if($this->expense) $this->expense->update(['amount' => $attributes['amount']]);
        if($this->transfer) $this->transfer->update(['amount' => $attributes['amount']]);
        parent::update($attributes, $options);
    }
    
    public function delete()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // if($this->salary) $this->salary->delete();
        // if($this->expense) $this->expense->delete();
        // if($this->payment) $this->payment->delete();
        // if($this->transfer) $this->transfer->delete();
        // if($this->cheque) $this->cheque->delete();
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
}