<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public const TYPE_DEBT = -1;
    public const TYPE_DEDUCATION = -2;
    public const TYPE_BONUS = 1;
    public const TYPES = [
    -1 => 'سلفة',
    -2 => 'خصم',
    1 => 'علاوة',
    ];
    protected $fillable = ['amount', 'month', 'type', 'details', 'safe_id', 'employee_id', 'entry_id', 'user_id', 'created_at'];
    
    public function employee(){
        return $this->belongsTo('App\Employee');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function safe(){
        return $this->belongsTo('App\Safe');
    }
    
    public function entry(){
        return $this->belongsTo('App\Entry', 'entry_id');
    }
    
    public static function create(array $attributes = [])
    {
        if($attributes['safe_id'] != 0){
            $entryId = null;
            if($attributes['type'] == Transaction::TYPE_DEDUCATION){
                $payment = Payment::create([
                'amount' => $attributes['amount'],
                'details' => 'عبارة عن '. self::TYPES[$attributes['type']] .' للموظف رقم: ' . $attributes['employee_id'],
                'to_id' => Account::revenues()->id,
                'from_id' => $attributes['employee_account'],
                'safe_id' => $attributes['safe_id'],
                ]);
                $entryId = $payment->entry_id;
            }else{
                $expense = Expense::create([
                'amount' => $attributes['amount'],
                'details' => 'عبارة عن '. self::TYPES[$attributes['type']] .' للموظف رقم: ' . $attributes['employee_id'],
                'safe_id' => $attributes['safe_id'],
                ]);
                $entryId = $expense->entry_id;
            }
            
            $attributes['entry_id'] = $entryId;
        }
        
        // dd($attributes);
        $attributes['user_id'] = auth()->user()->id;
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $model = static::query()->create($attributes);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $model;
    }
    
    public function update(array $attributes = [], array $options = []){
        if($this->entry) $this->entry->update(['amount' => $attributes['amount'], 'details' => $attributes['details']]);
        parent::update($attributes, $options);
    }
    
    public function delete(){
        if(isset($this->entry)) $this->entry->delete();
        parent::delete();
    }
}