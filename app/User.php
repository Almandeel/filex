<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Manager;
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use Manager;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'username', 'password', 'employee_id',
    ];
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
    'password', 'remember_token',
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    
    
    public function employee(){
        return $this->belongsTo('App\Employee', 'employee_id');
    }
    
    public function stores(){
        return $this->belongsToMany('App\Store');
    }

    public function storesIds(){
        return $this->stores->pluck('id')->toArray();
    }
    
    public function getStores(){
        return $this->isSuper() ? Store::all() : $this->stores;
    }
    
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
    
    public function bills() {
        return $this->hasMany('App\Bill');
    }
    
    public function invoices() {
        return $this->hasMany('App\Invoice');
    }
    
    public function storeBills() {
        return $this->hasManyThrough('App\Bill', 'App\Store');
    }
    
    public function storeInvoices() {
        return $this->hasManyThrough('App\Invoice', 'App\Store');
    }
    
    public function isSuper(){
        return $this->hasRole("superadmin");
    }
    
    public function suppliers(){
        $all = new Collection();
        $stores = $this->isSuper() ? Store::all() : $this->stores;
        foreach ($stores as $store) {
            $all = $all->merge($store->suppliers);
        }
        return $all;
    }
    
    public function customers(){
        $all = new Collection();
        $stores = $this->isSuper() ? Store::all() : $this->stores;
        foreach ($stores as $store) {
            $all = $all->merge($store->customers);
        }
        return $all;
    }
    
    public static function cashiers()
    {
        $markets = Role::market()->users;
        $cashiers = Role::cashier()->users;
        $users = $markets->concat($cashiers);
        return $users;
    }

    public static function market()
    {
        return Role::market()->users;
    }
    
    public static function create(array $attributes)
    {
        if (array_key_exists('employee_id', $attributes) && !array_key_exists('phone', $attributes)) {
            $employee = Employee::find($attributes['employee_id']);
            if ($employee->phone) {
                $attributes['phone'] = $employee->phone;
            }
        }
        
        return static::query()->create($attributes);
    }
    
    public function delete()
    {
        $this->roles()->detach();
        $this->permissions()->detach();
        return parent::delete();
    }
    
}