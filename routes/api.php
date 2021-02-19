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

/**
 * guest with id encrypt
 */
Route::post("tickets/message/guest/{id}", "Tickets\TicketsMessagesController@messageCustomer")->middleware("isValidEncrypt");
Route::get("tickets/showGuest/{id}", "Tickets\TicketsController@show")->middleware("isValidEncrypt");

Route::group(['middleware' => ['auth:api']], function(){

    Route::get('dashboard', 'DashboardController@index');
    Route::get('roles', 'RoleController@index');

    Route::group(["prefix" => "tickets"], function(){
        Route::post("admin", "Tickets\TicketsController@storeAdmin")->middleware("permission:portal admin");
        Route::post("customer", "Tickets\TicketsController@storeCustomer")->middleware("permission:portal customer");
        
        Route::post("message/admin", "Tickets\TicketsMessagesController@messageAdmin")->middleware("permission:portal admin");
        Route::post("message/customer", "Tickets\TicketsMessagesController@messageCustomer")->middleware("permission:portal customer");

    });

    Route::apiResources([
        'customers' => 'Customer\CustomerController',
        'contacts' => 'Contacts\ContactsController',
        'groups' => 'Groups\GroupsController',
        'users' => 'UserController',
        'systems' => 'Systems\SystemsController',
        "tickets" => "Tickets\TicketsController"
    ]);
    
});

