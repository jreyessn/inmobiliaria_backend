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

Route::get('images/{any}', 'Images\ImagesController@image')->where("any", ".*");

Route::group(['middleware' => ['auth:api']], function(){
    
    /**
     * Gets
     */
    Route::get('dashboard', 'DashboardController');
    Route::get('roles', 'RoleController@index');
    Route::get('categories_services', 'Services\CategoriesServicesController');
    Route::get('priorities_services', 'Controller@priorities');
    Route::get('reports/services', 'Reports\ReportsServicesController@services');
    Route::get('services/pdf/{id}', 'Services\ServicesController@pdfDetail');

    /**
     * Deletes
     */
    Route::delete('images/{any}', 'Images\ImagesController@destroy')->where("any", ".*");

    /**
     * Puts
     */
    Route::put("services/comply/{id}", "Services\ServicesController@comply");
    Route::put('notifications/push', 'NotificationController@savePlayerSignal');

    /**
     * Patchs
     */
    Route::patch("services/{id}", "Services\ServicesController@patchUpdate");

    Route::apiResources([
        'users'                 => 'UserController',
        'areas'                 => 'Areas\AreasController',
        'brands_equipments'     => 'Equipments\BrandsEquipmentsController',
        'categories_equipments' => 'Equipments\CategoriesEquipmentsController',
        'farms'                 => 'Farms\FarmsController',
        'spare_parts'           => 'Services\SparePartsController',
        'types_services'        => 'Services\TypesServicesController',
        'tools'                 => 'Tools\ToolsController',
        'equipments'            => 'Equipments\EquipmentsController',
        'services'              => 'Services\ServicesController',
    ]);

});
