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

Route::apiResources([
    'alerts' => 'API\AlertController',
    'clients' => 'API\ClientController',
    'watched-automated-processes' => 'API\WatchedAutomatedProcessController',
    'ui-path-orchestrators' => 'API\UiPathOrchestratorController',
    'alert-triggers' => 'API\AlertTriggerController'
]);
