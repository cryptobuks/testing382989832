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

Route::post('register', 'UserController@register')->name('api.jwt.register');
Route::post('login', 'UserController@login')->name('api.jwt.login');
Route::get('unauthorized', 'UserController@unauthorized')->name('api.jwt.unauthorized');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@user')->name('api.jwt.user');;
    Route::get('refresh', 'UserController@refresh')->name('api.jwt.refresh');
    Route::get('logout', 'UserController@logout')->name('api.jwt.logout');
});
