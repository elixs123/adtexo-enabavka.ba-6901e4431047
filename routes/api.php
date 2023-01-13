<?php

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

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    // Documents
	Route::get('/documents', 'DocumentController@index');
	Route::post('/documents/sync/{status}', 'DocumentController@sync');

    // Clients
    Route::resource('clients', ClientController::class)->only([
        'index', 'show', 'store', 'update'
    ])->parameters(['clients' => 'id']);

    // Products
    Route::post('/products/sync-prices', 'ProductController@syncPrices');
    Route::resource('products', ProductController::class)->only([
        'show', 'store'
    ]);

    // Brands
    Route::resource('brands', BrandController::class)->only([
        'show', 'store', 'index'
    ]);

    // Categories
    Route::post('categories/{id}/translate', 'CategoryController@translate');
    Route::resource('categories', CategoryController::class)->only([
        'show', 'store', 'update', 'index'
    ]);

    // Stocks
    Route::post('/stocks/product/stock', 'StockController@productStock');
    Route::post('/stocks/sync-qty', 'StockController@syncQty');
    Route::resource('stocks', StockController::class)->only([
        'show', 'store', 'index'
    ]);

    // Logs
	Route::resource('logs', LogController::class)->only([
		'index', 'show', 'store'
	]);
    
    // Persons
    Route::resource('persons', PersonController::class)->except([
        'destroy'
    ]);
    
    // Billing
    Route::post('/billings/insert', 'BillingController@insert');
    Route::resource('billings', 'BillingController')->only(['store', 'show']);
    
    // Demands
    Route::post('/demands/insert', 'DemandController@insert');
    Route::resource('demands', 'DemandController')->only(['store', 'show']);
    
    // Cities
    Route::resource('cities', CityController::class)->only([
        'index',
        'show',
    ]);
});

// Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
//     Route::get('/documents/{id}', 'DocumentController@show');
// });
