<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
    'id', 'name', 'phone', 'account_id'
    ];
    
    
    public function balance(){
        // return $this->credits->sum('amount') - $this->debts->sum('amount');
        return $this->bills->sum('amount') - $this->bills->sum('payed');
    }
    
    public function balanceType(){
        $b = $this->balance();
        return ($b > 0) ? 'دائن' : (($b < 0) ? 'مدين' : '');
    }
    
    public function balanceClass(){
        $b = $this->balance();
        return ($b > 0) ? 'warning' : (($b < 0) ? 'success' : 'default');
    }
    
    public function sell($bill, $details){
        $entry = $this->account->withDrawn($bill->amount, $bill->store->account_id, $details);
        $bill->entries()->attach([$entry->id]);
        return $entry;
    }
    
    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
    
    public function account(){
        return $this->belongsTo('App\Account');
    }
    
    public function fromEntries(){
        return $this->account->fromEntries;
    }
    
    public function toEntries(){
        return $this->account->toEntries;
    }
    
    public function cheques(){
        return $this->hasMany('App\Cheque', 'benefit_id');
    }
    
    public function stores(){
        return $this->belongsToMany('App\Store');
    }
    
    
    public static function create(array $attributes = [])
    {
        $groupId = Group::suppliers()->id;
        $account = Account::create(['name' => $attributes['name'], 'group_id' => $groupId]);
        $attributes['account_id'] = $account->id;
        $attributes['id'] = $account->id;
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        if($this->account){
            $this->account->update([ 'name' => $attributes['name']]);
        }else{
            $account = Account::create(['name' => $attributes['name'], 'group_id' => Group::suppliers()->id]);
            $attributes['account_id'] = $account->id;
        }
        return parent::update($attributes, $options);
    }
    
    // public function delete(){
    //     foreach ($this->users as $user) {
    //         $user->pivot->delete();
    //     }
    //     $this->resetBills();
    //     $this->resetInvoices();
    //     foreach ($this->itemUnits as $unit) {
    //         $unit->delete();
    //     }
    //     $account->delete();
    
    //     $result =  parent::delete();
    //     return $result;
    // }
    
}