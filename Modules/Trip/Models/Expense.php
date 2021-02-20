<?php

namespace Modules\Trip\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table  = "trip_expenses";
    protected $fillable = ['trip_id', 'amount', 'expense_type', 'notes'];
}
