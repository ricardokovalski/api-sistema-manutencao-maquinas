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

/*Route::group([
    'middleware' => '',
], function () {*/

    Route::post('auth/logout', 'Api\AuthController@logout');
    Route::post('auth/refresh', 'Api\AuthController@refresh');
    Route::post('auth/me', 'Api\AuthController@me');

    Route::get('dashboard', 'Api\DashboardController@index');

    Route::get('machines/send-email', 'Api\MachineController@sendEmail');

    Route::resource('machines', 'Api\MachineController')->except('create', 'edit');

    Route::post('machines/technical-manager', 'Api\MachineController@assignUser');
    Route::post('machines/technical-manager/remove', 'Api\MachineController@removeUser');

    Route::post('machines/piece', 'Api\MachineController@assignPiece');
    Route::post('machines/piece/remove', 'Api\MachineController@removePiece');

    Route::resource('maintenance', 'Api\MaintenanceController')->except('create', 'edit');
    Route::resource('pieces', 'Api\PeaceController')->except('create', 'edit');
    Route::resource('review-types', 'Api\ReviewTypeController')->except('create', 'store', 'show', 'edit', 'update', 'destroy');
    Route::resource('permissions', 'Api\PermissionController')->except('create', 'edit', 'update', 'destroy');
    Route::resource('roles', 'Api\RoleController')->except('create', 'edit', 'update', 'destroy');
    Route::resource('technical-managers', 'Api\TechnicalManagerController')->except('create', 'edit');
    Route::resource('users', 'Api\UserController')->except('create', 'edit');
//});
