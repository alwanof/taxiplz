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

use App\User;

Route::get('/', function () {
    return view('welcome');
})->name('start');

Route::get('/language/{language}', 'LanguageController@index')->name('switcher')->where('language', '[A-Za-z]+');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('lang/{locale}', 'HomeController@lang')->name('lang');

Route::prefix('admin')->group(function () {
    Route::get('/users', 'UserController@index')->name('users')->middleware('permission:access-users');
    Route::get('/roles', 'UserController@roles')->name('roles')->middleware('permission:access-roles');
    Route::get('/permissions', 'UserController@permissions')->name('permissions')->middleware('permission:access-permissions');
    Route::get('/profile/{user}', 'UserController@profile')->name('profile');
    Route::get('/me', 'UserController@me')->name('me');
    Route::get('/config', 'ConfigController@config')->name('config.index')->middleware('permission:access-configs');
    Route::get('/settings', 'ConfigController@settings')->name('config.settings');
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/orders/all/draft', 'OrderController@index')->name('orders.index');
    Route::get('/orders/all/pending', 'OrderController@pending_orders')->name('orders.pending');
    Route::get('/orders/all/approved', 'OrderController@approved_orders')->name('orders.approved');
    Route::get('/orders/all/completed', 'OrderController@completed_orders')->name('orders.completed');
    Route::get('/order/details/{id}', 'OrderController@show');
    Route::get('/order/create', 'OrderController@create')->name('orders.create');
    Route::post('/order/create', 'OrderController@store')->name('orders.store');
    Route::get('order/update/{id}', 'OrderController@edit')->name('orders.edit');
    Route::get('order/update/pending/{id}', 'OrderController@edit_pending_order')->name('orders.edit.pending');
    Route::post('/order/pending/update', 'OrderController@update');
    Route::get('order/update/approved/{id}', 'OrderController@edit_approved_order')->name('orders.edit.approved');
    Route::post('/order/approved/update', 'OrderController@updateApprovedOrder');
    Route::get('order/update/completed/{id}', 'OrderController@edit_completed_order')->name('orders.edit.completed');
    Route::post('/order/completed/update', 'OrderController@updateCompletedOrder');
    Route::get('/bills', 'BillController@index')->name('bills.index');
});
Route::get('order/view/{id}', 'OrderController@show_before_export');