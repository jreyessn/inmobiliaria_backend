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

Route::get("config", "Controller@config");

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\LoginController@login');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\LoginController@logout');
        Route::get('user', 'Auth\LoginController@user');
        Route::patch('preferences', 'UserController@updatePreferences');
    });
});

// Recovery Password

Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::get('find/{token}', 'ResetPasswordController@find');
    Route::post('create_token', 'ResetPasswordController@create');
    Route::post('reset', 'ResetPasswordController@reset');
});

Route::get('images/{any}', 'Images\ImagesController@image')->where("any", ".*");

Route::group(['middleware' => ['auth:api']], function(){
    

    /**
     * Config general
     */
    Route::group(["prefix" => "config"], function(){
        Route::put("/", "Controller@putConfig");
    });

    /**
     * Gets fetch simple data
     */
    Route::get('roles', 'RoleController@index');
    Route::get('measure_units', 'Furniture\MeasureUnitController@index');
    Route::get('type_furnitures', 'Furniture\TypeFurnitureController@index');
    Route::get('countries', 'Country\CountryController@index');
    Route::get('documents', 'Sale\DocumentController@index');
    Route::get('payment_methods', 'Sale\PaymentMethodController@index');

    /**
     * Deletes
     */
    Route::delete('images/{any}', 'Images\ImagesController@destroy')->where("any", ".*");

    Route::apiResources([
        'users'         => 'UserController',
        'cities'        => 'Country\CityController',
        'urbanitations' => 'Furniture\UrbanitationController',
        'customers'     => 'Customer\CustomerController',
        'furnitures'    => 'Furniture\FurnitureController',
    ]);


});
