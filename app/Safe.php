<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Safe extends Model
{
    public const TYPE_CASH = 1;
    public const TYPE_BANK = 2;
    public const TYPES = [
    self::TYPE_CASH => 'نقدية',
    self::TYPE_BANK => 'بنك',
    ];
    protected $fillable = [
    'id', 'name', 'type', 'opening_balance', 'show_bills', 'show_invoices', 'show_expenses', 'show_cheques', 'account_id'
    ];
    
    public function getType(){
        return self::TYPES[$this->type];
    }
    
    public function balance(){
        return $this->account->fromEntries->sum('amount') - $this->account->toEntries->sum('amount');
    }
    
    public static function cashes(){
        return self::where('type', self::TYPE_CASH)->get();
    }
    
    public static function banks(){
        return self::where('type', self::TYPE_BANK)->get();
    }
    
    public function cheques()
    {
        return $this->hasMany('App\Cheque', 'account_id');
    }
    
    public function account(){
        return $this->belongsTo('App\Account');
    }
    
    public function newPayment($data){
        $data['to_id'] = $this->account_id;
        return Entry::newPayment($data);
    }
    
    public function newExpense($data){
        $data['from_id'] = $this->account_id;
        return Entry::newExpense($data);
    }
    
    public function newIncome($data){
        $data['from_id'] = $this->account_id;
        return Entry::newIncome($data);
    }
    
    public function transfers(){
        return Transfer::where('from_id', $this->id)->orwhere('to_id', $this->id)->get();
    }
    
    public function toTransfers(){
        return $this->hasMany(Transfer::class, 'to_id');
    }
    
    public function fromTransfers(){
        return $this->hasMany(Transfer::class, 'from_id');
    }
    
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    
    public function expenses(){
        return $this->hasMany(Expense::class);
    }
    
    public static function billsSafes() { return Safe::where('show_bills', 1)->get(); }
    public static function invoicesSafes() { return Safe::where('show_invoices', 1)->get(); }
    public static function expensesSafes() { return Safe::where('show_expenses', 1)->get(); }
    public static function chequesSafes() { return Safe::where('show_cheques', 1)->get(); }
    public static function cashSafes($show = null) {
        $safes = Safe::where('type', self::TYPE_CASH);
        if($show){
            $safes->where('show_' . $show, 1);
        }
        return $safes->get();
    }
    public function billsEntries($from_date = null, $to_date = null, $users_ids = null)
    {
        $stores = auth()->user()->getStores();
        if(!$users_ids){
            $users_ids = [];
            foreach ($stores as $store) {
                foreach ($store->users as $user) {
                    if(!in_array($user->id, $users_ids)){
                        $users_ids[] = $user->id;
                    }
                }
            }
        }
        $entries = $this->paymentsEntries()->filter(function($entry) use ($users_ids){
            return $entry->to_id == $this->id && in_array($entry->user_id, $users_ids);
        });
        // dd($entries);
        if($from_date && $to_date) return $entries->whereBetween('created_at', [$from_date, $to_date]);
        return $entries;
    }
    public function invoicesEntries($from_date = null, $to_date = null, $users_ids = null)
    {
        $stores = auth()->user()->getStores();
        if(!$users_ids){
            $users_ids = [];
            foreach ($stores as $store) {
                foreach ($store->users as $user) {
                    if(!in_array($user->id, $users_ids)){
                        $users_ids[] = $user->id;
                    }
                }
            }
        }
        $entries = $this->paymentsEntries()->filter(function($entry) use ($users_ids){
            return $entry->from_id == $this->id && in_array($entry->user_id, $users_ids);
        });
        if($from_date && $to_date) return $entries->whereBetween('created_at', [$from_date, $to_date]);
        return $entries;
    }
    
    public function paymentsEntries()
    {
        $entries_ids = $this->payments->pluck('entry_id');
        $entries = Entry::whereIn('id', $entries_ids)->get();
        return $entries;
    }
    
    public function invoices()
    {
        return $this->hasManyThrough('App\Invoice', 'App\Entry', 'from_id');
    }
    
    public static function bankSafes($show = null) {
        $safes = Safe::where('type', self::TYPE_BANK);
        if($show){
            $safes->where('show_' . $show, 1);
        }
        return $safes->get();
    }
    public static function entries($side = null, $row = false){
        $safes = Safe::all();
        $entries = null;
        if($safes->count()){
            foreach ($safes as $index => $safe) {
                if($side){
                    $entries = ($index == 0) ? Entry::where($side, $safe->id) : $entries->orwhere($side, $safe->id);
                }else{
                    $entries = ($index == 0) ? Entry::where('from_id', $safe->id)->orwhere('to_id', $safe->id) : $entries->orwhere('from_id', $safe->id)->orwhere('to_id', $safe->id);
                }
            }
            if(!$row) $entries = $entries->get();
        }
        return $entries;
    }
    
    public static function create(array $attributes = [])
    {
        $groupId = Group::safes()->id;
        $account = Account::create(['name' => $attributes['name'], 'group_id' => $groupId]);
        $attributes['account_id'] = $account->id;
        $attributes['id'] = $account->id;
        
        if(!array_key_exists('opening_balance', $attributes)){
            $attributes['opening_balance'] = 0;
        }else{
            if(!$attributes['opening_balance']){
                $attributes['opening_balance'] = 0;
            }
        }
        $model = static::query()->create($attributes);
        if($model->opening_balance){
            $transfer = Transfer::create([
            'amount'    => $model->opening_balance,
            'from_id'   => $model->id,
            'to_id'     => Account::capital()->id,
            'details'   => 'عبارة عن رصيد افتتاحي للخزنة رقم: ' . $model->id,
            'type'      => Entry::TYPE_OPEN,
            ]);
        }
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        if($this->account){
            $this->account->update([ 'name' => $attributes['name']]);
        }else{
            $account = Account::create(['name' => $attributes['name'], 'group_id' => Group::safes()->id]);
            $attributes['account_id'] = $account->id;
        }
        if(array_key_exists('opening_balance', $attributes)){
            $openEntry = $this->account->openingEntries()->last();
            if($openEntry){
                $openEntry->update(['amount' => $attributes['opening_balance']]);
            }else{
                $transfer = Transfer::create([
                'amount'    => $attributes['opening_balance'],
                'from_id'   => $this->id,
                'to_id'     => Account::capital()->id,
                'details'   => 'عبارة عن رصيد افتتاحي للخزنة رقم: ' . $this->id,
                'type'      => Entry::TYPE_OPEN,
                ]);
            }
        }
        parent::update($attributes, $options);
    }
    
    public function delete(){
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($this->transfers() as $transfer) {
            $transfer->delete();
        }
        foreach ($this->payments as $payment) {
            $payment->delete();
        }
        foreach ($this->expenses as $expense) {
            $expense->delete();
        }
        $result = parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
}