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

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('users', 'API\UserController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('products', 'API\ProductController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('orders', 'API\OrderController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('roles', 'API\RoleController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('permissions', 'API\PermissionController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('configs', 'API\ConfigurationController', ['except' => ['create', 'edit', 'show']]);
    Route::apiResource('settings', 'API\SettingController', ['only' => ['index', 'update']]);
    Route::apiResource('bills', 'API\BillController', ['except' => ['create', 'edit', 'show']]);
    Route::get('search/users', 'API\UserController@search');
    Route::get('search/products', 'API\ProductController@search');
    Route::get('search/bills', 'API\BillController@search');
    Route::get('search/orders/draft', 'API\OrderController@search');
    Route::get('search/orders/pending', 'API\OrderController@search_pending_orders');
    Route::get('search/orders/approved', 'API\OrderController@search_approved_orders');
    Route::get('search/orders/completed', 'API\OrderController@search_completed_orders');
    Route::get('orders/all/pending','API\OrderController@pending_orders');
    Route::get('orders/all/approved','API\OrderController@approved_orders');
    Route::get('orders/all/completed','API\OrderController@completed_orders');
    Route::post('toggle/role', 'API\UserController@toggleRole');
    Route::post('toggle/permission', 'API\UserController@togglePerm');
    Route::post('toggle/config/role', 'API\ConfigurationController@toggleRole');
    Route::get('search/roles', 'API\ConfigurationController@search');
    Route::get('search/permissions', 'API\PermissionController@search');
    Route::get('search/configs', 'API\ConfigurationController@search');
    Route::get('search/settings', 'API\SettingController@search');
    Route::get('order/{id}/products','API\OrderController@get_order_products');
    Route::delete('order/item/{id}','API\ItemController@remove_item_from_order');
    Route::get('products/all','API\ProductController@get_all_product_for_order');
    Route::post('item/new','API\ItemController@add_new_item');
    Route::get('order/{id}/informations','API\OrderController@get_order_info');
    Route::put('item/update/{id}','API\ItemController@update');
});
