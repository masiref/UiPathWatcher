<?php

use App\Alert;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathRobot;

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

// Authentication routes
Auth::routes();

// Home
Route::get('/', 'DashboardController@index')->name('dashboard');

// Layout controller
Route::get('/layout/menu/{page?}', 'LayoutController@menu')->name('layout.menu');
Route::get('/layout/sidebar/{page?}', 'LayoutController@sidebar')->name('layout.sidebar');

// Dashboard controller
Route::get('/dashboard/tiles/{client?}', 'DashboardController@tiles')->name('dashboard.tiles');

Route::get('/dashboard/alert/table/{closed?}/{id?}', 'DashboardController@alertTable')->name('dashboard.alert.table');
Route::get('/dashboard/alert/table-row/{alert}', 'DashboardController@alertTableRow')->name('dashboard.alert.table.row');
Route::get('/dashboard/alert/element/{alert}', 'DashboardController@alertElement')->name('dashboard.alert.element');

Route::get('/dashboard/client/elements', 'DashboardClientController@elements')->name('dashboard.client.elements');
Route::get('/dashboard/client/element/{client}', 'DashboardClientController@element')->name('dashboard.client.element');
Route::get(
    '/dashboard/client/{client}/watched-automated-process/elements/{autonomous}',
    'DashboardClientController@watchedAutomatedProcessElements'
)->name('dashboard.client.watched-automated-process.elements');
Route::get('/dashboard/client/{client}', 'DashboardClientController@index')->name('dashboard.client');
Route::get('/dashboard/client/alert/table/{client}/{closed}/{id}', 'DashboardClientController@alertTable')
    ->name('dashboard.client.alert.table');

Route::get('/dashboard/user', 'DashboardUserController@index')->name('dashboard.user');
Route::get('/dashboard/user/alert/table/{closed}/{id}', 'DashboardUserController@alertTable')->name('dashboard.user.alert.table');
Route::get('/dashboard/user/tiles', 'DashboardUserController@tiles')->name('dashboard.user.tiles');

Route::get('/dashboard/watched-automated-process/element/{watchedAutomatedProcess}/{autonomous?}',
    'DashboardController@watchedAutomatedProcessElement')->name('dashboard.watched-automated-process.element');

// Configuration controller
Route::get('/configuration/orchestrator', 'ConfigurationOrchestratorController@index')->name('configuration.orchestrator');
Route::get('/configuration/orchestrator/table', 'ConfigurationOrchestratorController@table')->name('configuration.orchestrator.table');
Route::get('/configuration/orchestrator/processes/{client}', 'ConfigurationOrchestratorController@processes')->name('configuration.orchestrator.processes');

Route::get('/configuration/client', 'ConfigurationClientController@index')->name('configuration.client');
Route::get('/configuration/client/table', 'ConfigurationClientController@table')->name('configuration.client.table');

Route::get('/configuration/watched-automated-process', 'ConfigurationWatchedAutomatedProcessController@index')
    ->name('configuration.watched-automated-process');
Route::get(
    '/configuration/watched-automated-process/table',
    'ConfigurationWatchedAutomatedProcessController@table'
)->name('configuration.watched-automated-process.table');

Route::get('/configuration/alert-triggers', 'ConfigurationAlertTriggerController@index')->name('configuration.alert-trigger');
