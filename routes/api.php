<?php


use Illuminate\Support\Facades\Route;

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

// Auth

Route::group(['prefix' => 'auth','namespace' => 'Auth'], function () {
    Route::post('login', 'LoginController@login');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'LoginController@logout');
        Route::get('user', 'LoginController@user');
    });
});

// Recovery Password

Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::get('find/{token}', 'ResetPasswordController@find');
    Route::post('create_token', 'ResetPasswordController@create');
    Route::post('reset', 'ResetPasswordController@reset');
});


Route::group(['middleware' => ['auth:api']], function(){

    Route::get('dashboard', 'DashboardController@index');
    Route::get('questions', 'Question\QuestionController');
    
    Route::group(['prefix' => 'reports'], function(){
        Route::post('frequency_supervisor', 'Reports\ReportsController@frequencySupervisor');
        Route::post('supervisor_month', 'Reports\ReportsController@supervisorMonth');
        Route::post('farm_month', 'Reports\ReportsController@farmsMonth');
    });

    Route::get('visits/download/{id}', 'Visit\VisitController@downloadVisitReport');

    Route::apiResources([
        'users' => 'UserController',
        'roles' => 'RoleController',
        'farms' => 'Farm\FarmController',
        'visits' => 'Visit\VisitController'
    ]);

});