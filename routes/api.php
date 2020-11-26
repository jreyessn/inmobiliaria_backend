<?php

use App\Http\Controllers\TreasuryController;
use Illuminate\Http\Request;
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

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
], function () {
    Route::post('login', 'LoginController@login');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'LoginController@logout');
        Route::get('user', 'LoginController@user');
    });
});

// Password

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create_token', 'ResetPasswordController@create');
    Route::post('reset', 'ResetPasswordController@reset');
    Route::get('find/{token}', 'ResetPasswordController@find');
});

Route::get('type_providers', 'TypeProviderController');

Route::group(['middleware' => 'auth:api'], function(){

    Route::get('dashboard', 'DashboardController@index');
    
    Route::get('files/terminos', 'FileController@showTerminos');
    Route::get('files/terminos/{download?}', 'FileController@showTerminos');
    Route::get('files/{id}/{download?}', 'FileController@show');
    Route::post('files/update', 'FileController@update');
    Route::apiResource('files', 'FileController');
    
    // endpoins relativos a la funcionalidad de proveedores (registro, aprobaciones, listados)

    Route::get('providers/document/{id}/{download?}', 'Provider\ProviderController@showDocument');
    Route::get('providers/request_edit_show/{id}', 'Provider\ProviderController@requestEditShow');
    Route::post('providers/request_edit_information', 'Provider\ProviderController@requestEditInformation');
    Route::post('providers/approved_edit_information', 'Provider\ProviderController@approvedEditInformation');
    Route::post('providers/change_status', 'Provider\ProviderDocumentController@changeStatus');
    Route::post('providers/update_document', 'Provider\ProviderDocumentController@updateDocument');
    Route::post('providers/contract', 'Provider\ProviderController@contract');
    Route::post('providers/inactive', 'Provider\ProviderController@inactive');
    Route::post('providersUpdate', 'Provider\ProviderController@update');
    Route::apiResource('providers', 'Provider\ProviderController');

    Route::put('applicant_providers/change_status/{id}', 'ApplicantProviders\ApplicantProvidersController@changeStatus');
    Route::get('applicant_providers/download_authorization/{id}', 'ApplicantProviders\ApplicantProvidersController@downloadAuthorization');
    Route::apiResource('applicant_providers', 'ApplicantProviders\ApplicantProvidersController');

    // endpoins relativos a la funcionalidad de dar de alta de proveedores para sap

    Route::get('providers_sap/authorizations/{provider_sap_id}', 'Provider\ProviderSapAuthorizationController@show');
    Route::post('providers_sap/authorize', 'Provider\ProviderSapAuthorizationController@store');
    Route::get('providers_sap/download_xlsx_sap/{provider_sap_id}', 'Provider\ProviderSapAuthorizationController@downloadExcelSap');
    Route::apiResource('providers_sap', 'Provider\ProviderSapController');

    Route::apiResource('users','UserController');
    Route::apiResource('roles','RoleController');

    Route::get('document', 'DocumentController@index');
    Route::post('document', 'DocumentController@update');
    Route::delete('document/{id}', 'DocumentController@destroy');
    Route::get('document/{id}/{download?}', 'DocumentController@show');

    /* Invokes */
    
    Route::get('business_types', 'BusinessTypeController');
    Route::get('treasury_groups', 'TreasuryController');
    Route::get('bank_country', 'BankCountryController');
    Route::get('bank', 'BankController');
    Route::get('retention_indicator', 'RetentionIndicatorController');
    Route::get('retention_type', 'RetentionTypeController');
    Route::get('accounts_group', 'AccountsGroupController');
    Route::get('organization', 'OrganizationController');
    Route::get('society', 'SocietyController');
    Route::get('treatment', 'TreatmentController');
    Route::get('associated_account', 'AssociatedAccountController');
    Route::get('payment_condition', 'PaymentConditionController');
    Route::get('payment_method', 'PaymentMethodController');
    Route::get('tolerance_group', 'ToleranceGroupController');
    Route::get('currency', 'CurrencyController');
    Route::get('type_bank_interlocutor', 'TypeBankInterlocutorController');

    Route::get('countries', 'CountriesController@getCountries');
    Route::get('states', 'CountriesController@getStates');
    Route::get('cities', 'CountriesController@getCities');


});