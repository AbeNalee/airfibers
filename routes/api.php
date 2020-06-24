<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/store', function(){
    $map = date('Y-m-d H:i:s');
    return $map;

});
Route::post('confirm', ['as' => 'safpayments', 'uses'=>'PaymentController@confirmC2b']);
Route::post('validate', ['as' => 'safpayments2', 'uses'=>'PaymentController@validateC2b']);
Route::post('paying', ['as' => 'safpayments3', 'uses'=>'PaymentController@safaricom']);


Route::get('code', 'PaymentController@formatPhone');
