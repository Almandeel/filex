<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $fillable = [
    'name', 'display_name', 'description',
    ];
    public const DEFAULTS = [1, 2, 3];
    public function isDefault(){
        return in_array($this->id, self::DEFAULTS);
    }

    public function isSuper(){
        return $this->name == 'superadmin';
    }

    public function getNameAttribute($name)
    {
        if ($this->isDefault()) {
            return __('roles.defaults.' . $this->id);
        }
        return $name;
    }

    public static function superadmin()
    {
        return static::find(1);
    }

    public static function cashier()
    {
        return static::find(2);
    }

    public static function market()
    {
        return static::find(3);
    }
    public function delete(){
        if(!$this->isDefault()){
            $result =  parent::delete();
            return $result;
        }
        
        return null;
    }
    
}