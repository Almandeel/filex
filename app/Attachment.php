<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Storage;
class Attachment extends BaseModel
{
    public const OFFICE_INTERNAL = 'internal';
    public const OFFICE_EXTERNAL = 'external';
    protected $fillable = ['name', 'file', 'office', 'attachable_type', 'attachable_id', 'user_id'];
    protected const IMAGES_MIMES = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/svg'];
    public function attachable()
    {
        return $this->morphTo();
    }
    
    public function isImage(){
        if(file_exists($this->getFilePath())){
            return in_array($this->getFile()->getMimeType(), self::IMAGES_MIMES);
        }
        return false;
    }
    
    public function isNote(){
        if(isset($this->file)){
            return strlen($this->file) <= 0;
        }
        return true;
    }
    
    public function getFile(){
        if(file_exists($this->getFilePath())){
            return new \Symfony\Component\HttpFoundation\File\File($this->getFilePath());
            // return $this->getUploadedFile($this->getFilePath());
        }
        return null;
    }
    
    public function fileExists(){
        if(!is_null($this->file)){
            return file_exists($this->getFilePath());
        }
        return false;
    }
    
    public function getFilePath(){
        return public_path('attachments_files' . DIRECTORY_SEPARATOR . $this->file);
    }
    public function getFileURL(){
        return url('attachments_files/' . $this->file);
    }
    
    public function update(array $attributes = [], array $options = []){
        return parent::update($attributes, $options);
    }
    
    // public static function create(array $attributes = []){
    //     if(auth()->guard('office')->check()){
    //         $attributes['office'] = self::OFFICE_EXTERNAL;
    //     }
    //     $attachment = static::query()->create($attributes);
    //     return $attachment;
    // }
    
    public function deleteFile(){
        $file = $this->getFilePath();
        // dd($file, $this->file);
        if(file_exists($file) && $this->file) return unlink($file);
        return false;
    }
    
    public function delete(){
        $this->deleteFile();
        return parent::delete();
    }
    
    
    // public function auth()
    // {
    //     if($this->fromExternalOffice()){
    //         return $this->morphToMany('Modules\ExternalOffice\Models\User', 'authable')->first();
    //     }
    //     return $this->morphToMany(User::class, 'authable')->first();
    // }
    
    public function fromExternalOffice(){
        return $this->office == self::OFFICE_EXTERNAL;
    }
    
    public function isEdittable(){
        return
        (!$this->fromExternalOffice() && !auth()->guard('office')->check()) ||
        ($this->fromExternalOffice() && auth()->guard('office')->check());
    }
    
    public function getUploadedFile($url)
    {
        //get name file by url and save in object-file
        $path_parts = pathinfo($url);
        //get image info (mime, size in pixel, size in bits)
        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if(!is_dir ($newPath)){
            mkdir($newPath, 0777);
        }
        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        $imgInfo = getimagesize($newUrl);
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(
        $newUrl,
        $path_parts['basename'],
        $imgInfo['mime'],
        filesize($url),
        TRUE
        );
        return $file;
    }
    
    public function getFileObject()
    {
        $url = public_path('image/demo.jpg');
        $files = $this->getUploadedFile($url);
        dd($files);
    }
}