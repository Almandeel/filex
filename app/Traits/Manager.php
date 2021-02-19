<?php
namespace App\Traits;
use Modules\Restaurant\Models\{Order};
/**
 *  Manager Trait
 */
trait Manager
{
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
