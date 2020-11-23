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

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});

JsonApi::register('default')->routes(function ($api) {
    $api->resource('alerts');
    $api->resource('alert-triggers');
    $api->resource('alert-trigger-definitions');
    $api->resource('watched-automated-processes');
    $api->resource('clients');
    $api->resource('orchestrators');
    $api->resource('users');
});