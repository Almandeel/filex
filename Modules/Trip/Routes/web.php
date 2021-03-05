<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('trip')->group(function() {
    Route::get('/', 'TripController@index');
    Route::resource('/drivers', 'DriverController');
    Route::resource('/cars', 'CarController');
    Route::resource('/states', 'StateController');
    Route::resource('/trips', 'TripController');
    Route::resource('/expensestrips', 'TripExpenseController');
    Route::get('/archives', 'TripController@archive')->name('trips.archive');
});
