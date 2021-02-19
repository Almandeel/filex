<?php

namespace App\Providers;

use App\Cheque;
use Carbon\Carbon;
use App\Notifications\ChequeNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //
    }
    
    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        if(env('APP_TIMEZONE')){
            date_default_timezone_set(env('APP_TIMEZONE'));
        }
        
        $this->sync();
        \Blade::if('can', function ($string) {
            // if($string == 'price-purchase-read') dd(Auth::user()->allPermissions(), Auth::user()->allPermissions()->where('name', $string)->first());
            $condition = false;
            
            $permissions = is_array($string) ? $string : explode('|', $string);
            foreach ($permissions as $permission) {
                $condition = \Auth::user()->allPermissions()->where('name', $permission)->first() ? true : false;
            }
            
            return $condition;
        });
        
        Schema::defaultStringLength(191);
            
            if (now()->format('H:i') == '15:00') {
                foreach (Cheque::all() as $cheque) {
                    $dueDateFormat = Carbon::parse($cheque->due_date)->subDays(1)->format('y-m-d');
                    if ($cheque->status == Cheque::STATUS_DELEVERED && $dueDateFormat == now()->format('y-m-d')) {
                        auht()->user()->notify(new ChequeNotification($cheque));
                }
            }
        }
    }
    
    public function sync() {
        // Connect to live database
        // $live_database = DB::connection('remote_mysql');
        // // Get table data from production
        // foreach($live_database->table('table_name')->get() as $data){
        //     // Save data to staging database - default db connection
        //     DB::table('table_name')->insert((array) $data);
        // }
        // // Get table_2 data from production
        // foreach($live_database->table('table_2_name')->get() as $data){
        //     // Save data to staging database - default db connection
        //     DB::table('table_2_name')->insert((array) $data);
        // }
    }
}