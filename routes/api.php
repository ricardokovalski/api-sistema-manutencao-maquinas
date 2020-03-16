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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('authenticate', 'AuthController@authenticate');
Route::post('unauthenticated', 'AuthController@unauthenticated');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');

Route::resource('users', 'Api\UserController')->except('create', 'edit');