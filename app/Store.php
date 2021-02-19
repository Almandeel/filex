<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Store extends Model
{
    protected $fillable = [
    'id', 'name', 'account_id'
    ];
    
    public function users(){
        return $this->belongsToMany('App\User');
        // this->belongsToMany('App\Role', 'role_user_table', 'user_id', 'role_id');
    }
    
    public function suppliers(){
        return $this->belongsToMany('App\Supplier');
    }
    
    public function customers(){
        return $this->belongsToMany('App\Customer');
    }
    
    public function item($id){
        return ItemStore::findOrFail($id);
    }

    public function itemStores(){
        return $this->hasMany('App\ItemStore');
    }
    
    public function items(){
        return $this->belongsToMany('App\Item')->withPivot('id', 'store_id', 'item_id');
    }
    
    public function bills(){
        return $this->hasMany('App\Bill');
    }
    
    public function invoices(){
        return $this->hasMany('App\Invoice');
    }
    
    public function resetBills(){
        foreach ($this->bills as $bill) {
            $bill->delete();
        }
    }
    
    public function resetInvoices(){
        foreach ($this->invoices as $invoice) {
            $invoice->delete();
        }
    }
    
    public function itemStoreUnit($itemStoreId, $itemUnitId){
        return ItemStoreUnit::where('item_store_id', $itemStoreId)->where('item_unit_id', $itemUnitId)->first();
    }
    
    public function account(){
        return $this->belongsTo('App\Account');
    }
    
    public static function create(array $attributes = [])
    {
        $groupId = Group::stores()->id;
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
            $account = Account::create(['name' => $attributes['name'], 'group_id' => Group::stores()->id]);
            $attributes['account_id'] = $account->id;
        }
        parent::update($attributes, $options);
    }
    
    public function itemUnits() {
        return $this->hasMany('App\ItemUnit');
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
    
    //     $result =  parent::delete();
    //     return $result;
    // }
}