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
Route::get('/plex-try', 'PaymentController@plex');
Route::get('/', 'PackagesController@index');
Route::get('/store', 'PackagesController@store');
Route::post('/', 'PackagesController@voucher');
Route::get('/guest/s/default', 'PackagesController@index');
//Route::post('/payment', 'PaymentController@create');
Route::post('/payment-online', 'PaymentController@online');
Route::post('/payment-offline', 'PaymentController@offline');
//Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'PaymentController@paymentsuccess']);
//Route::post('/donepayment', 'PaymentController@paymentConfirmation');
Route::post('/donepayment', 'PaymentController@paymentConfirmed');
Route::get('/voucher', 'VoucherController@index')->name('voucher');
Route::get('/voucher-sent', 'VoucherController@sent')->name('paid');
Route::post('/voucher', 'VoucherController@verify');
Route::post('/first', 'PaymentController@first');
Route::post('/second', 'PaymentController@second');
Route::get('/pay/{id}', 'PaymentController@view')->name('payment');
Route::get('weekend-offer', 'PackageController@weekend');

Auth::routes();
//Route::get('/querystatus', 'PaymentController@query');

/*
 * Test routes
 */
Route::view('/test/store-package-page', 'test.store.package');
Route::get('/test/store-unlimited-only', 'PackagesController@storeUnlimitedOnly');