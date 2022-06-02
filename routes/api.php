<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::post('login', 'API\V1\AuthController@login');
Route::post('refresh-token', 'API\V1\AuthController@refreshToken');

Route::group(['middleware' => ['auth:api','role:Field Staff']], function(){
    Route::post('logout', 'API\V1\AuthController@logout');
});

Route::group(['prefix'=> 'v1', 'middleware' => ['auth:api','role:Field Staff']], function(){
    Route::get('user-detail', 'StaffController@getStaffDetail');
    Route::get('assigned-entities-forms', 'API\V1\EntityFormController@index');
    Route::get('assigned-entities-forms/{entities_form}', 'API\V1\EntityFormController@show');
    Route::get('entities-form-data/rejected', 'API\V1\EntityFormDataController@rejected');
    Route::get('entities-form-data', 'API\V1\EntityFormDataController@listFilledFormList');
    Route::resource('assigned-entities-forms/{entities_form}/entities-form-data', 'API\V1\EntityFormDataController')->except('create','edit', 'update');
    
    Route::post('entities-form-data/{entitydata_id}', 'API\V1\EntityFormDataController@updateRejectedData');
    Route::get('report-forms-assigned', 'API\V1\ReportDataController@getAssignedReport');
    Route::get('report-forms-data', 'API\V1\ReportDataController@getReportDataList');
    Route::get('report-forms-rejected', 'API\V1\ReportDataController@getRejectedReport');
    Route::post('report-forms', 'API\V1\ReportDataController@store');
    Route::post('report-data-update', 'API\V1\ReportDataController@update');

    Route::post('staff-attendance', 'API\V1\StaffAttendanceController@store');
    Route::post('staff-location', 'API\V1\StaffLocationController@store');

    Route::post('stock/outward-stock', 'API\V1\StockController@outwardStock');
    Route::get('stock/categories', 'API\V1\StockController@getCategoryList');
    Route::get('stock/items/{categoryId}', 'API\V1\StockController@getItemList');
    Route::get('stock/regions', 'API\V1\StockController@getRegionList');
    Route::get('stock/entity/{regionId}', 'API\V1\StockController@getEntityList');
    Route::get('stock/item-onstock/{itemId}', 'API\V1\StockController@getItemCurrentStock');
    Route::get('stock/document-type', 'API\V1\DocumentTypeController@index');
    Route::get('get-stock-detail/{unique_id}', 'API\V1\InwardStockController@getSNDetail');
    Route::post('sim/outward-stock', 'API\V1\OutwardStockController@store');
    Route::get('outward-stock-detail', 'API\V1\OutwardStockController@outwardStockDetail');
    Route::get('outward-stock-detail-report', 'API\V1\OutwardStockController@outwardStockDetailReport');
});