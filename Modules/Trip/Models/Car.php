<?php

namespace Modules\Trip\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_TRIP = 1;
    protected $fillable = ['vin_number', 'car_number', 'empty_weight', 'max_weight', 'status'];
}
