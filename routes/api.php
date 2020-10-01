<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login',  'App\Http\Controllers\api\auth\LoginController@login');
    Route::post('signup', 'App\Http\Controllers\api\auth\LoginController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'App\Http\Controllers\api\auth\LoginController@logout');
        Route::get('user',   'App\Http\Controllers\api\auth\LoginController@user');
    });
});

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::get('/getAll', 'App\Http\Controllers\api\ReservationController@getAll');
    Route::get('/getAllByUser',  'App\Http\Controllers\api\ReservationController@getAllByUser');
    Route::get('/getAllBySpace', 'App\Http\Controllers\api\ReservationController@getAllBySpace');

    Route::post('/store', 'App\Http\Controllers\api\ReservationController@store');
    
});

//This route avoids going to login when auth fails
Route::get('loginfailed', function () {     
    return response()->json(['message' => 'Unauthenticated'], 401);
})->name('loginfail');
