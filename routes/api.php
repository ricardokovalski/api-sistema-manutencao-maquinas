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

Route::post('auth/login', 'Api\AuthController@login');

Route::group([
    'middleware' => 'cross.domain',
], function () {

    Route::post('auth/logout', 'Api\AuthController@logout');
    Route::post('auth/refresh', 'Api\AuthController@refresh');
    Route::post('auth/me', 'Api\AuthController@me');

    Route::resource('machines', 'Api\MachineController')->except('create', 'edit');
    Route::post('machines/technical-manager', 'Api\MachineController@assignUser');

    Route::resource('maintenance', 'Api\MaintenanceController')->except('create', 'edit');
    Route::resource('pieces', 'Api\PeaceController')->except('create', 'edit');
    Route::resource('review-types', 'Api\ReviewTypeController')->except('create', 'store', 'show', 'edit', 'update', 'destroy');
    Route::resource('permissions', 'Api\PermissionController')->except('create', 'edit', 'update', 'destroy');
    Route::resource('roles', 'Api\RoleController')->except('create', 'edit', 'update', 'destroy');
    Route::resource('technical-managers', 'Api\TechnicalManagerController')->except('create', 'edit');
    Route::resource('users', 'Api\UserController')->except('create', 'edit');
});
