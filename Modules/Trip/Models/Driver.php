<?php

namespace Modules\Trip\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['uid', 'name', 'phone', 'address'];
}
