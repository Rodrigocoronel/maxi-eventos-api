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

Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', function (Request $request) {
    	return $request->user();
    });

    Route::post('/logout', function(Request $request){
    	$request->user()->token()->revoke();
    	return response()->json([]);
    });

    /**Items**/
    Route::get('/items', 'ItemsController@index');
    Route::get('/itemsByUser/{id}' , 'ItemsController@itemsByUser');

    /**Orders**/
    Route::post('/guardarOrden', 'OrdenController@create');
    Route::get('/getOrders/{rol}', 'OrdenController@index');
    Route::get('/getOrderByid/{id}' , 'OrdenController@getOrderByid');
    Route::put('/updateOrden/{id}', 'OrdenController@update' );

    /**Users**/

    Route::post('/registerUser', 'UserController@create');
    Route::get('/getUsers' , 'UserController@index');
    Route::get('/getUserByid/{id}', 'UserController@getUserByid');
    Route::put('/updateUser/{id}' , 'UserController@update');

});