<?php

namespace Modules\Trip\Models;

use Modules\Trip\Models\Car;
use Modules\Trip\Models\State;
use Modules\Trip\Models\Driver;
use Modules\Trip\Models\Expense;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_DONE = 1;

    protected $fillable = ['from', 'to', 'car_id', 'driver_id' , 'amount', 'status'];
    
    public function fromState() {
        return $this->belongsTo(State::class, 'from');
    }

    public function toState() {
        return $this->belongsTo(State::class, 'to');
    }

    public function car() {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function driver() {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getExpensesAmount() {
        return $this->hasMany(Expense::class);
    }
}
