<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $fillable = [
    'id', 'name', 'phone', 'account_id'
    ];
    
    
    public function balance(){
        return $this->account->toEntries->sum('amount') - $this->account->fromEntries->sum('amount');
    }
    
    public function balanceType(){
        $b = $this->balance();
        return ($b > 0) ? 'دائن' : (($b < 0) ? 'مدين' : '');
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
        $groupId = Group::collaborators()->id;
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
            $account = Account::create(['name' => $attributes['name'], 'group_id' => Group::collaborators()->id]);
            $attributes['account_id'] = $account->id;
        }
        parent::update($attributes, $options);
    }
}