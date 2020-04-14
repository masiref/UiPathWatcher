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

// Debug
Route::get('/debug', 'AppController@debug')->name('debug');

// App
Route::post('/app/shutdown-alert-triggers', 'AppController@shutdownAlertTriggers')->name('app.shutdown-alert-triggers');
Route::post('/app/reactivate-alert-triggers', 'AppController@reactivateAlertTriggers')->name('app.reactivate-alert-triggers');

// Home
Route::get('/', 'DashboardController@index')->name('dashboard');

// Layout controller
Route::get('/layout/menu/{page?}', 'LayoutController@menu')->name('layout.menu');
Route::get('/layout/sidebar/{page?}', 'LayoutController@sidebar')->name('layout.sidebar');
Route::get('/layout/hero', 'LayoutController@hero')->name('layout.hero');

// Dashboard controller
Route::get('/dashboard/tiles/{client?}', 'DashboardController@tiles')->name('dashboard.tiles');

Route::get('/dashboard/alert/table/{closed?}/{id?}', 'DashboardController@alertTable')->name('dashboard.alert.table');
Route::get('/dashboard/alert/table-row/{alert}', 'DashboardController@alertTableRow')->name('dashboard.alert.table.row');
Route::get('/dashboard/alert/element/{alert}', 'DashboardController@alertElement')->name('dashboard.alert.element');
Route::get('/dashboard/quick-board/{client?}', 'DashboardController@quickBoard')->name('dashboard.quick-board');

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
Route::get('/configuration/orchestrator/edit/{orchestrator}', 'ConfigurationOrchestratorController@edit')->name('configuration.orchestrator.edit');
Route::get('/configuration/orchestrator/table', 'ConfigurationOrchestratorController@table')->name('configuration.orchestrator.table');
Route::get('/configuration/orchestrator/processes/{client}', 'ConfigurationOrchestratorController@processes')->name('configuration.orchestrator.processes');
Route::get('/configuration/orchestrator/robots/{client}', 'ConfigurationOrchestratorController@robots')->name('configuration.orchestrator.robots');
Route::get('/configuration/orchestrator/queues/{client}', 'ConfigurationOrchestratorController@queues')->name('configuration.orchestrator.queues');

Route::get('/configuration/client', 'ConfigurationClientController@index')->name('configuration.client');
Route::get('/configuration/client/edit/{client}', 'ConfigurationClientController@edit')->name('configuration.client.edit');
Route::get('/configuration/client/table', 'ConfigurationClientController@table')->name('configuration.client.table');

Route::get('/configuration/watched-automated-process', 'ConfigurationWatchedAutomatedProcessController@index')
    ->name('configuration.watched-automated-process');
Route::get(
    '/configuration/watched-automated-process/table',
    'ConfigurationWatchedAutomatedProcessController@table'
)->name('configuration.watched-automated-process.table');

Route::get('/configuration/alert-trigger', 'ConfigurationAlertTriggerController@index')->name('configuration.alert-trigger');
Route::get('/configuration/alert-trigger/default-alert-trigger-details/{watchedAutomatedProcess}/{title?}', 'ConfigurationAlertTriggerController@defaultAlertTriggerDetails')
    ->name('configuration.alert-trigger.default-alert-trigger-details');
Route::get('/configuration/alert-trigger/default-alert-trigger-definition/{rank}', 'ConfigurationAlertTriggerController@defaultAlertTriggerDefinition')
    ->name('configuration.alert-trigger.default-alert-trigger-definition');
Route::get('/configuration/alert-trigger/default-alert-trigger-rule/{watchedAutomatedProcess}/{rank}/{type}', 'ConfigurationAlertTriggerController@defaultAlertTriggerRule')
    ->name('configuration.alert-trigger.default-alert-trigger-rule');
Route::post('/configuration/alert-trigger/default-alert-trigger-summary/{watchedAutomatedProcess}', 'ConfigurationAlertTriggerController@defaultAlertTriggerSummary')
    ->name('configuration.alert-trigger.default-alert-trigger-summary');
Route::post('/configuration/alert-trigger/alert-trigger-creation-confirmation/{alertTrigger}', 'ConfigurationAlertTriggerController@alertTriggerCreationConfirmation')
    ->name('configuration.alert-trigger.alert-trigger-creation-confirmation');
Route::get(
    '/configuration/alert-trigger/table',
    'ConfigurationAlertTriggerController@table'
)->name('configuration.alert-trigger.table');
