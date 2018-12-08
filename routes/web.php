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
Route::post('/payment', 'PaymentController@create');
Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'PaymentController@paymentsuccess']);
Route::post('/donepayment', 'PaymentController@paymentConfirmation');
Route::get('/voucher', 'VoucherController@index')->name('voucher');
Route::post('/voucher', 'VoucherController@verify');
