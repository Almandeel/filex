<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = ['total', 'net', 'debts', 'deducations', 'bonus', 'month', 'employee_id', 'entry_id', 'user_id'];
    
    public function employee(){
        return $this->belongsTo('App\Employee');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function entry(){
        return $this->belongsTo('App\Entry', 'entry_id');
    }
    
    public function transactions(){
        return App\Transaction::where('month', $this->month)->get();
    }
    
    public static function create(array $attributes = [])
    {
        $expense = Expense::create([
        'amount' => $attributes['net'],
        'details' => 'عبارة عن مرتب شهر '. $attributes['month'] .' للموظف رقم: ' . $attributes['employee_id'],
        'safe_id' => $attributes['safe_id'],
        ]);
        $attributes['entry_id'] = $expense->entry_id;
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        return $model;
    }
    public function update(array $attributes = [], array $options = []){
        if($this['entry']->expense) $this['entry']->expense->update(['amount' => $attributes['net'], 'safe_id' => $attributes['safe_id']]);
        parent::update($attributes, $options);
    }
    public function delete(){
        if(isset($this['entry']->expense)) $this['entry']->expense->delete();
        if(isset($this->entry)) $this->entry->delete();
        parent::delete();
    }
}