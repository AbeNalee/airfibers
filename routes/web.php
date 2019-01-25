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
Route::get('/', 'PackagesController@index');
Route::post('/', 'PackagesController@voucher');
Route::get('/guest/s/default', 'PackagesController@store');
Route::post('/payment', 'PaymentController@create');
Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'PaymentController@paymentsuccess']);
Route::post('/donepayment', 'PaymentController@paymentConfirmation');
Route::get('/voucher', 'VoucherController@index')->name('voucher');
Route::get('/voucher-sent', 'VoucherController@sent')->name('paid');
Route::post('/voucher', 'VoucherController@verify');
Route::post('/first', 'PaymentController@first');
Route::post('/second', 'PaymentController@second');
Route::get('/agent', 'AgentController@index');
//Route::get('/voucher', function () use ($mac) {
//    dd($mac);
//});
Route::get('/self-service', 'VoucherController@service');
