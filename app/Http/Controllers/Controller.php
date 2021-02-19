<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use PHPJasper\PHPJasper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // protected function print($report, $folder, $params = [], $format = ['pdf', 'docx'], $locale = 'ar', $options = null){
    //     $jasper = new PHPJasper;
    //     $input = public_path('reports/'.$folder.'/'.$report.'.jrxml');
    //     $output = public_path('reports/'.$folder);
    //     $options = ($options) ? $options : [
    //         'format' => $format,
    //         'locale' => $locale,
    //         'params' => $params,
    //         'db_connection' => [
    //             'driver' => env('DB_CONNECTION', 'mysql'),
    //             'host' => env('DB_HOST', 'localhost'),
    //             'port' => env('DB_PORT', '3306'),
    //             'database' => env('DB_DATABASE', 'shamel'),
    //             'username' => env('DB_USERNAME', 'admin'),
    //             'password' => env('DB_PASSWORD', 'admin'),
    //         ]
    //     ];
    //     $jasper->process(
    //             $input,
    //             $output,
    //             $options
    //     )->execute();

    //     return 'reports/'.$folder.'/'.$report.'.pdf';
    // }
}
