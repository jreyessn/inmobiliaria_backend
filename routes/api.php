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

Route::group(['prefix' => 'tickets/guest'], function(){
    
    Route::post("store", "Tickets\TicketsController@storeGuest");

    Route::group([ 'middleware' => ['isValidEncrypt'] ], function(){

        Route::post("message/{id}", "Tickets\TicketsMessagesController@messageCustomer");
        Route::get("file/{file_id}/{id}", "Tickets\TicketsMessagesController@downloadAttach")->middleware('fileBelongsTicket');
        Route::get("show/{id}", "Tickets\TicketsController@show");
        Route::get("message/{id}", "Tickets\TicketsMessagesController@showMessages");
    });
});

/**
 * public save images
 */
Route::post("images", "ImageController@save");
Route::get('type_tickets', 'TypeTicketController');

Route::group(['middleware' => ['auth:api']], function(){

    Route::get('dashboard', 'DashboardController@index');
    Route::get('roles', 'RoleController@index');
    Route::get('priorities', 'PriorityController');
    Route::get('status_tickets', 'StatusTicketController');
    Route::get('created', 'Controller@createdOptions');
    Route::get('deadline', 'Controller@expirationsOptions');
    Route::get('dashboard', 'DashboardController');

    Route::group(["prefix" => "tickets"], function(){
        Route::post("admin", "Tickets\TicketsController@storeAdmin")->middleware("permission:portal admin");
        Route::post("message/admin", "Tickets\TicketsMessagesController@messageAdmin")->middleware("permission:portal admin");

        Route::post("customer", "Tickets\TicketsController@storeCustomer")->middleware("permission:portal customer");
        Route::post("message/customer", "Tickets\TicketsMessagesController@messageCustomer")->middleware("permission:portal customer");
        Route::post("forwardInternal", "Tickets\TicketsController@forwardInternal");
        
        Route::get("message/{ticket_id}", "Tickets\TicketsMessagesController@showMessages");
        Route::get("attach/{id}", "Tickets\TicketsMessagesController@downloadAttach");

        Route::put("tracked/{id}", "Tickets\TicketsController@tracked");
    });

    Route::group(["prefix" => "reports"], function(){
        Route::get("timeForSystems", "ReportsController@timeForSystems");
        Route::get("ticketsReport", "ReportsController@ticketsReport");
    });

    /**
     * put profile customers
     */
    Route::put("contacts/profile", 'Contacts\ContactsController@update')->middleware("permission:portal customer");

    Route::apiResources([
        'customers' => 'Customer\CustomerController',
        'contacts' => 'Contacts\ContactsController',
        'groups' => 'Groups\GroupsController',
        'users' => 'UserController',
        'systems' => 'Systems\SystemsController',
        "tickets" => "Tickets\TicketsController"
    ]);
    
});

