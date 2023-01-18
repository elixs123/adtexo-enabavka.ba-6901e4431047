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

Route::group(['namespace' => 'Api', 'prefix' => '{db}'], function () {
    Route::get('subjects/search', 'SubjectController@search');
    Route::get('projects/search', 'ProductController@search');
    Route::get('projects/search/rabat', 'ProductController@searchRabat');
});


Route::group(['namespace' => 'Api', 'prefix' => '{db}', 'middleware' => 'auth:api'], function () {
    // Documents
	Route::get('/documents', 'DocumentController@index');
	Route::post('/documents/sync/{status}', 'DocumentController@sync');

    // Clients
    Route::resource('clients', ClientController::class)->only([
        'index', 'show', 'store', 'update'
    ])->parameters(['clients' => 'id']);



    // Brands
    Route::get('brands', 'BrandController@getAll');
    Route::get('brands/{id}', 'BrandController@getOne');
    
    Route::post('brands/insert', 'BrandController@insertBrand');
    Route::post('brands/update/{id}', 'BrandController@updateBrand');

    // Categories
    Route::get('categories', 'CategoryController@index');
    Route::post('categories/insert', 'CategoryController@saveCategory');
    Route::post('categories/update/{id}', 'CategoryController@updateCategory');

    //Products
    Route::get('products', 'ProductController@index');
    Route::post('products/insert', 'ProductController@insertProduct');
    Route::post('products/update/{id}', 'ProductController@updateProduct');

    //Subjects
    Route::get('subjects', 'SubjectController@index');
    Route::post('subjects/insert', 'SubjectController@saveSubject');
    Route::post('subjects/update/{id}', 'SubjectController@updateSubject');

    // Stocks
    Route::post('/stocks/product/stock', 'StockController@productStock');
    Route::post('/stocks/sync-qty', 'StockController@syncQty');
    Route::resource('stocks', StockController::class)->only([
        'show', 'store', 'index'
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
