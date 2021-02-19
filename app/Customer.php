<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
    'id', 'name', 'phone', 'account_id'
    ];
    
    
    public function balance(){
        // return $this->debts->sum('amount') - $this->credits->sum('amount');
        return $this->invoices->sum('amount') - $this->invoices->sum('payed');
    }
    
    public function balanceType(){
        $b = $this->balance();
        return ($b < 0) ? 'دائن' : (($b > 0) ? 'مدين' : '');
    }
    
    public function balanceClass(){
        $b = $this->balance();
        return ($b < 0) ? 'success' : (($b > 0) ? 'warning' : 'default');
    }
    
    
    public function debts()
    {
        return $this->hasMany('App\Entry', 'from_id');
    }
    
    
    public function credits()
    {
        return $this->hasMany('App\Entry', 'to_id');
    }
    
    
    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'customer_id');
    }
    
    public function payments()
    {
        return $this->hasManyThrough('App\Payment', 'App\Entry', 'to_id');
    }
    
    public function stores(){
        return $this->belongsToMany('App\Store');
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
    
    public static function create(array $attributes = [])
    {
        $groupId = Group::customers()->id;
        $account = Account::create(['name' => $attributes['name'], 'group_id' => $groupId]);
        $attributes['account_id'] = $account->id;
        // $attributes['id'] = $account->id;
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        if($this->account){
            $this->account->update([ 'name' => $attributes['name']]);
        }else{
            $account = Account::create(['name' => $attributes['name'], 'group_id' => Group::customers()->id]);
            $attributes['account_id'] = $account->id;
        }
        parent::update($attributes, $options);
    }


    public function subscriptions() {
        return $this->hasMany('Modules\Subscription\Models\Subscription')->withTrashed();
    }
}