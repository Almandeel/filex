<?php

namespace App;

use App\Traits\Accountable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Employee extends Model
{
    use Accountable;
    protected $fillable = [
    'name', 'phone', 'address', 'salary'
    ];
    
    
    public function user(){
        return $this->hasOne('App\User');
    }
    
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
    
    public function salaries(){
        return $this->hasMany('App\Salary');
    }
    
    public function monthlyTransactions($month = null){
        $month = $month == null ? date('Y-m') : $month;
        return $this->transactions->where('month', $month);
    }
    
    public function getUserNameAttribute()
    {
        if ($this->user) {
            return $this->user->username;
        }
    }
    
    public function dailyEntries($date){
        $account = $this->account;
        $from_date_time = $date . ' 00:00:00';
        $to_date_time = $date . ' 23:59:59';
        return Entry::where('from_id', $account->id)->orwhere('to_id', $account->id)->whereBetween('created_at', [$from_date_time, $to_date_time])->orderBy('id')->get();
    }
    public function openingEntry($date = null){
        $account = $this->account;
        return Entry::where('from_id', $account->id)->where('type', Entry::TYPE_JOURNAL)->get();
        // $from_date_time = ' 00:00:00';
        // $to_date_time = ' 23:59:59';
        // return Entry::where('from_id', $account->id)->whereBetween('created_at', [$from_date_time, $to_date_time])->orderBy('id')->where('type', Entry::TYPE_JOURNAL)->first();
    }

    public function closeEntry($date = null){
        $account = $this->account;
        // $from_date_time = $date . ' 00:00:00';
        // $to_date_time = $date . ' 23:59:59';
        return Entry::where('to_id', $account->id)->where('type', Entry::TYPE_JOURNAL)->get();
        // return Entry::where('to_id', $account->id)->whereBetween('created_at', [$from_date_time, $to_date_time])->orderBy('id')->where('type', Entry::TYPE_JOURNAL)->first();
    }


    public static function isOpeningEntry($cashier) {
        $opening_entry = $cashier->openingEntry();
        $close_entry = $cashier->closeEntry();

        if($opening_entry > $close_entry) {
            return true;
        }

        return false;
    }
    
    
    public static function create(array $attributes)
    {
        $account = Account::create([
        'name' => $attributes['name'],
        'group_id' => Group::cashiers()->id,
        ]);
        
        $model = static::query()->create($attributes);
        $model->account()->save($account);
        
        return $model;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        $result = parent::update($attributes, $options);
        if (array_key_exists('name', $attributes) && !is_null($this->account)) {
            $this->account->update(['name' => $attributes['name']]);
        }
        if ($this->user && array_key_exists('phone', $attributes)) {
            $this->user->update(['phone' => $attributes['phone']]);
        }
        return $result;
    }
    
    public function isCashier()
    {
        if ($this->user) {
            return $this->user->roles->contains(2);
        }
        return false;
    }
    
    public static function cashiers()
    {
        $users = User::cashiers();
        $employees = new Collection();
        foreach ($users as $user) {
            $employees->push($user->employee);
        }
        return $employees;
        // return User::cashiers()->map(function($users){
        //     return $users->map(function ($user) {
        //         dd($user);
        //         if ($user->employee) {
        //             return $user->employee;
        //         }
        //     });
        // });
    }
    public function openSafe($accountId, $amount, $details = null)
    {
        if ($this->account) {
            $details = is_null($details) ? 'عبارة عن قيد فتح خزنة موظف رقم: ' . $this->id . ' ليوم: ' . date('Y/m/d') : $details;
            $entry = Entry::create([
            'from_id' => $this->account->id,
            'to_id' => $accountId,
            'amount' => $amount,
            'details' => $details,
            'type' => Entry::TYPE_JOURNAL,
            ]);
        }
        
        return $this;
    }
    
    public function closeSafe($entries)
    {
        if ($this->account) {
            foreach ($entries as $entry) {
                if (is_array($entry)) {
                    if (array_key_exists('amount', $entry)) {
                        if (array_key_exists('to_id', $entry) || array_key_exists('from_id', $entry)) {
                            $entry_data = $entry;
                            if (array_key_exists('to_id', $entry)) {
                                $entry_data['from_id'] = $this->account->id;
                            }
                            elseif (array_key_exists('from_id', $entry)) {
                                $entry_data['to_id'] = $this->account->id;
                            }
                            Entry::create($entry_data);
                        }
                    }
                }
            }
        }
        
        return $this;
    }
    public function delete(){
        //     foreach ($this->transactions as $transaction) {
        //         $transaction->delete();
        //     }
        //     foreach ($this->salaries as $salary) {
        //         $salary->delete();
        //     }
        //     $salary->user();
        if($this->user) $this->user->delete();
        $result =  parent::delete();
        return $result;
    }
}