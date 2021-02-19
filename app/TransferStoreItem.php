<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferStoreItem extends Model
{
    protected $fillable = ['item_store_unit_id', 'transfer_store_id', 'quantity'];

    public function itemStoreUnit() {
        return $this->belongsTo('App\ItemStoreUnit', 'item_store_unit_id');
    }
}
