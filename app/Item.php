<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\Restaurant\Models\{Menu};

class Item extends Model
{
    protected $fillable = [
    'name', 'image', 'category_id', 'barcode'
    ];
    
    public function units()
    {
        /*
        $messages  = Message::where('message_id', $id)->get();
        foreach($messages as $message)
        $message->users()->updateExistingPivot($user, array('status' => 1), false);
        */
        return $this->belongsToMany(Unit::class)->withPivot('id', 'price');
    }
    
    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withPivot('status')->withTimestamps();
    }
    
    public function stores()
    {
        return $this->belongsToMany(Store::class)->withPivot('id');
    }

    public function category()
    {
        return $this->belongsTo("App\Category", 'category_id');
    }
    
    public function unitsId()
    {
        return $this->units()->pluck('unit_id');
    }
    
    public function itemUnits()
    {
        return $this->hasMany('App\ItemUnit');
    }
    
    // public function delete(){
    //     foreach ($this->stores as $store) {
    //         $store->pivot->delete();
    //     }
    //     foreach ($this->itemUnits as $unit) {
    //         $unit->delete();
    //     }
    
    //     $result =  parent::delete();
    //     return $result;
    // }
    
    
    
    public function getImage(){
        if(file_exists($this->image_path)){
            return new \Symfony\Component\HttpFoundation\Image\Image($this->image_path);
            // return $this->getUploadedImage($this->image_path);
        }
        return null;
    }
    
    public function imageExists(){
        if(!is_null($this->image)){
            return file_exists($this->image_path);
        }
        return false;
    }
    
    public function getImagePathAttribute(){
        return public_path('images/items' . DIRECTORY_SEPARATOR . $this->image);
    }

    public function getImageUrlAttribute(){
        $image_name = $this->imageExists() ? $this->image : 'default.png';
        return url('images/items/' . $image_name);
    }
    
    public function deleteImage(){
        $image = $this->image_path;
        if(file_exists($image) && $this->image) return unlink($image);
        return false;
    }

    public function update(array $attributes = [], array $options = []){
        if (array_key_exists('image', $attributes)) {
            $this->deleteImage();
        }

        return parent::update($attributes, $options);
    }
    
    public function delete(){
        $this->deleteImage();
        $this->menus()->detach();
        $this->units()->detach();
        $this->stores()->detach();
        return parent::delete();
    }
    
}