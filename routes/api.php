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

Route::group(['prefix' => "customers"], function(){
    Route::get('qr/{id}', 'Customer\CustomerController@qr');
});


Route::group(['middleware' => ['auth:api']], function(){

    /**
     * gets simples
     */
    Route::get('dashboard', 'DashboardController');
    Route::get('roles', 'RoleController@index');
    Route::get('type_movements', 'controller@typeMovements');
    Route::get('payment_methods', 'controller@paymentMethods');
    Route::get('visits/is_visited_customer', 'Visits\VisitsController@hasVisitedToday');

    /**
     * puts
     */
    Route::put("user/profile", 'UserController@updateProfile');
    Route::put("coupons_request/approver/{coupon_request}", 'Coupons\CouponsRequestController@approver');

    /**
     * Reports
     */
    Route::get("reports/deliveries", 'Reports\ReportsSalesController@dailyDeliveries');
    Route::get("reports/renewal_customers", 'Reports\ReportsSalesController@renewalCustomerCoupons');
    Route::get("reports/sales_monthly", 'Reports\ReportsSalesController@salesMonthly');
    Route::get("statistics", 'Reports\StatisticsController@graphics');
    
    
    Route::apiResources([
        'customers' => 'Customer\CustomerController',
        'users' => 'UserController',
        'coupons_request' => 'Coupons\CouponsRequestController',
        'coupons_movements' => 'Coupons\CouponsMovementsController',
        'visits' => 'Visits\VisitsController',
    ]);
    
});

