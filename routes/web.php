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
    return view('auth.login');
})->name('start');

Route::get('/create/{office?}/{name?}/{phone?}/{address?}', 'StartController@create')->name('start.craete');

Route::post('/order/compose', 'StartController@compose')->name('order.compose');


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
    Route::get('/drivers', 'DriverController@index')->name('drivers.index');
    Route::get('drivers/profile/{driver}', 'DriverController@profile')->name('drivers.profile');
    Route::get('/members', 'UserController@members')->name('users.members');
});