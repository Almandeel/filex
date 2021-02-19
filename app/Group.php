<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
    'id', 'name', 'group_id',
    ];
    
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
    
    public static function assets() { return self::where('name', 'assets')->first(); }
    
    public static function currentAssets() { return self::where('name', 'currentAssets')->first(); }
    
    public static function cashiers() { return self::where('name', 'cashiers')->first(); }
    
    public static function stores() {return self::where('name', 'stores')->first(); }
    
    public static function safes() {return self::where('name', 'safes')->first(); }

    public static function customers() {return self::where('name', 'customers')->first(); }

    public static function collaborators() {return self::where('name', 'collaborators')->first(); }
    
    public static function liabilities() {return self::where('name', 'liabilities')->first(); }
    
    public static function suppliers() {return self::where('name', 'suppliers')->first(); }
    
    public static function revenues() {return self::where('name', 'expenses')->first(); }
    
    public static function expenses() {return self::where('name', 'expenses')->first(); }
    
}