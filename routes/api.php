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
    $api->resource('alerts')->only('index', 'read');
    $api->resource('alert-categories')->only('index', 'read');
    $api->resource('alert-triggers')->only('index', 'read');
    $api->resource('alert-trigger-definitions')->only('index', 'read');
    $api->resource('alert-trigger-rules')->only('index', 'read');
    $api->resource('watched-automated-processes')->only('index', 'read');
    $api->resource('clients')->only('index', 'read');
    $api->resource('ui-path-orchestrators')->only('index', 'read');
    $api->resource('ui-path-robots')->only('index', 'read');
    $api->resource('ui-path-processes')->only('index', 'read');
    $api->resource('ui-path-queues')->only('index', 'read');
    $api->resource('users')->only('index', 'read');
});