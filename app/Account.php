<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Group, Entry};
class Account extends Model
{
    private $defaults = [
    'safe' => 'النقدية', 'bank' => 'البنك', 'capital' => 'رأس المال'
    ];
    protected $fillable = [
        'name', 'group_id',
    ];

    public function accountable()
    {
        return $this->morphTo();
    }
    
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
    
    public function getName(){
        if(in_array($this->name, array_keys($this->defaults))) return $this->defaults[$this->name];
        return $this->name;
    }
    
    public static function safe() { return self::where('name', 'safe')->first(); }
    
    public static function bank() { return self::where('name', 'bank')->first(); }
    
    public static function debts() { return self::where('name', 'debts')->first(); }
    
    public static function credits() { return self::where('name', 'credits')->first(); }
    
    public static function revenues() { return self::where('name', 'revenues')->first(); }
    
    public static function capital() { return self::where('name', 'capital')->first(); }
    
    public static function sales() { return self::where('name', 'sales')->first(); }
    
    public static function salesReturns() { return self::where('name', 'salesReturns')->first(); }
    
    public static function purchases() { return self::where('name', 'purchases')->first(); }
    
    public static function purchasesCharges() { return self::where('name', 'purchasesCharges')->first(); }
    
    public static function purchasesReturns() { return self::where('name', 'purchasesReturns')->first(); }
    
    public static function expenses() { return self::where('name', 'expenses')->first(); }
    
    public function fromEntries(){
        return $this->hasMany('App\Entry', 'from_id');
    }
    
    public function toEntries(){
        return $this->hasMany('App\Entry', 'to_id');
    }
    
    public function entries(){
        return Entry::where('from_id', $this->id)->orwhere('to_id', $this->id)->get();
    }
    
    public function cheques(){
        return $this->hasMany('App\Cheque', 'account_id');
    }
    
    public function reset(){
        foreach($this->fromEntries as $entry) {
            $entry->delete();
        }
        foreach($this->toEntries as $entry) {
            $entry->delete();
        }
    }
    
    public function openingEntries(){
        return $this->fromEntries->where('type', Entry::TYPE_OPEN);
    }
    
    public function balance($debtsSide = true){
        
        if($debtsSide) return $this->fromEntries->sum('amount') - $this->toEntries->sum('amount');
        return $this->toEntries->sum('amount') - $this->fromEntries->sum('amount');
    }
    
    public function balanceType(){
        $b = $this->balance();
        return ($b > 0) ? 'دائن' : (($b < 0) ? 'مدين' : '');
    }
    
    public function deposite($amount, $accountId, $details = ""){
        return Entry::create(['amount' => $amount, 'details' => $details, 'from_id' => $this->id, 'to_id' => $accountId, 'type' => Entry::TYPE_JOURNAL]);
    }
    
    public function withDrawn($amount, $accountId, $details = ""){
        return Entry::create(['amount' => $amount, 'details' => $details, 'to_id' => $this->id, 'from_id' => $accountId, 'type' => Entry::TYPE_JOURNAL]);
    }
    
    public static function newSupplier($name = 'supplier'){
        return Account::create(['name' => $name, 'group_id' => Group::suppliers()->id]);
    }
    
    public static function newCustomer($name = 'customer'){
        return Account::create(['name' => $name, 'group_id' => Group::customers()->id]);
    }
    
    public static function newCollaborator($name = 'collaborator'){
        return Account::create(['name' => $name, 'group_id' => Group::collaborators()->id]);
    }
    
    public static function newSafe($name = 'safe'){
        return Account::create(['name' => $name, 'group_id' => Group::safes()->id]);
    }
}