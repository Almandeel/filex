<?php

namespace Modules\Trip\Models;

use Modules\Trip\Models\Trip;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_TRIP = 1;
    protected $fillable = ['vin_number', 'car_number', 'empty_weight', 'max_weight', 'status'];

    public function trips() {
        return $this->hasMany(Trip::class);
    }

    public function getTotalExpenses() {
        $total_expenses = 0;
        foreach ($this->trips as $trip) {
            $total_expenses += $trip->getExpensesAmount()->sum('amount');
        }
        return $total_expenses;
    }
}
