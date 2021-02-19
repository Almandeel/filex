<?php
namespace App\Traits;
use App\Attachment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait Attachable{
    public static $extraArguments;
    
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    
    public function files(){
        return $this->attachments->filter(function($attachment){ return !$attachment->isNote(); });
    }
    public function notes(){
        return $this->attachments->filter(function($attachment){ return $attachment->isNote(); });
    }
    
    public function attach(Request $request = null){
        $request = $request ?: request();
        if($request->attachments_names){
            $names = is_array($request->attachments_names) ? $request->attachments_names : [];
            if(count($names)){
                for ($i=0; $i < count($names); $i++) {
                    $input_file_name = 'attachments_file' . ($i + 1);
                    $name = $names[$i];
                    $file = $request->has($input_file_name) ? $request->$input_file_name : null;
                    $attributes = ['name' => $name];
                    if(auth()->guard('office')->check()){
                        $attributes['office'] = Attachment::OFFICE_EXTERNAL;
                    }
                    if($file){
                        $newFileName = $this->generateFileName($file);
                        $file->move(public_path("/attachments_files"), $newFileName);
                        $attributes['file'] = $newFileName;
                    }
                    
                    $this->attachments()->create($attributes);
                }
            }
        }
    }
    
    public function resetAttachments(){
        foreach ($this->attachments as $attachment) {
            $attachment->delete();
        }
    }
    
    protected function generateFileName($file)
    {
        // return Str::random(16) . date('Ymdhis') . '.' . $file->getClientOriginalExtension();
        return Str::random(16) . date('Ymdhis') . '.' . $file->extension();
    }
    
    public static function bootAttachable()
    {
        static::created(function($model){
            // $model->attach();
        });
        
        static::deleting(function($model){
            foreach ($model->attachments as $attachment) {
                $attachment->delete();
            }
        });
    }
}