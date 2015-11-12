<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

Route::group(['prefix' => 'console', 'middleware' => [ 'cs.jwt']], function(){    
    
    Route::get('welcome', function() { return 'test';});
});

// EveryBody can access these paths !!!!!!
Route::group(['prefix' => 'artisan'], function(){
        
    Route::get('new', function(){
        
         \Artisan::call('migrate');  
         
         return 'Called "artisan migrate" ...';       
    });
    
    Route::get('refresh', function(){
        
         \Artisan::call('migrate:refresh'); 
         
         return 'Called "artisan migrate:refresh" ';       
    });
});
