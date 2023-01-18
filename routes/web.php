<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group(['namespace' => 'Auth'], function () {
    // Login


    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    
    // Register
    // Route::get('/register', 'RegisterController@showRegistrationForm')->name('auth.register');
    // Route::post('/register', 'RegisterController@register');
    
    // Password
    Route::group(['prefix' => 'password'], function() {
        Route::get('/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/reset', 'ResetPasswordController@reset');
    });
});
//Auth::routes();

// Route::post('pusher/auth', 'Pusher\AuthController@check')->name('pusher.auth');

// Lang
Route::post('/lang/change', 'LangController@change')->name('lang.change');

Route::group(['middleware' => ['auth', 'acl', 'emptystringstonull', 'user.person.client']], function () {
    // Logout
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    
    /*
    |--------------------------------------------------------------------------
    | Home Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/', 'HomeController@index')->name('dashboard');

    /**
     * 
     * Pantheon
     */

    Route::get('/createorder', 'PantheonController@index')->name('createorder');
    Route::post('/createorder', 'PantheonController@insert');
    /*
    |--------------------------------------------------------------------------
    | Invoicing Routes
    |--------------------------------------------------------------------------
    */
    Route::get('invoicing', 'InvoiceController@index')->name('invoicing');
    Route::get('invoicing/printInvoice/{id}', 'InvoiceController@printInvoice')->name('invoicing.printInvoice');
    Route::get('invoicing/getDocument/{id}', 'InvoiceController@getDocument')->name('invoicing.getDocument');
    Route::post('invoicing/saveFiscalData/{id}', 'InvoiceController@saveFiscalData')->name('invoicing.saveFiscalData');
    Route::post('invoicing/saveFiscalVoidData/{id}', 'InvoiceController@saveFiscalVoidData')->name('invoicing.saveFiscalVoidData');
    Route::get('invoicing/getFiscalRequest/{id}', 'InvoiceController@getFiscalRequest')->name('invoicing.getFiscalRequest');

    /*
    |--------------------------------------------------------------------------
    | CodeBook Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('code-book', 'CodeBookController', ['parameters' => ['code-book' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Role Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/role/manage-permissions/{id}', 'RoleController@getManagePermissions')->name('role.permission.edit');
    Route::post('/role/manage-permissions/{id}', 'RoleController@postManagePermissions')->name('role.permission.update');
    Route::resource('role', 'RoleController', ['parameters' => ['role' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Permission Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('permission', 'PermissionController', ['parameters' => ['permission' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user/login-as/{id}', 'UserController@loginAs');
    Route::get('/user/login-as-real-user', 'UserController@loginAsRealUser');
    Route::resource('user', 'UserController', ['parameters' => ['user' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Activity Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('activity', 'ActivityController', ['only' => ['index']]);

    /*
    |--------------------------------------------------------------------------
    | Stock Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('stock', 'StockController', ['parameters' => ['stock' => 'id']]);

    /*
    |--------------------------------------------------------------------------
    | Brand Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('brand', 'BrandController', ['parameters' => ['brand' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Category Routes
    |--------------------------------------------------------------------------
    */
    Route::get('category/translate/{id}/{lang_id}', 'CategoryController@getTranslate');
    Route::post('category/translate/{id}', 'CategoryController@postTranslate');
    Route::resource('category', 'CategoryController', ['parameters' => ['category' => 'id']]);

    /*
    |--------------------------------------------------------------------------
    | Product Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('product/quantity', 'Product\QuantityController@parse');
    Route::get('product/translate/{id}/{lang_id}', 'ProductController@getTranslate');
    Route::post('product/translate/{id}', 'ProductController@postTranslate');
    Route::get('product/search', 'ProductController@search')->name('product.search');
    Route::get('product/{id}/quantities', 'Product\StockController@index')->name('product.stocks');
    Route::resource('product', 'ProductController', ['parameters' => ['product' => 'id']]);
    Route::post('item-photo/ajax-upload', 'PhotoController@postAjaxUpload');
    Route::post('item-photo/remove', 'PhotoController@postRemove');
	
    /*
    |--------------------------------------------------------------------------
    | Product Stock Routes
    |--------------------------------------------------------------------------
    */
    Route::get('product-stock/mass/create', 'ProductStockController@massCreate')->name('product_stock.mass_create');
    Route::post('product-stock/mass/create', 'ProductStockController@massStore')->name('product_stock.mass_store');
    Route::resource('product-stock', 'ProductStockController', ['parameters' => ['product_stock' => 'id']]);
	
    /*
    |--------------------------------------------------------------------------
    | Shop Routes
    |--------------------------------------------------------------------------
    */
	Route::get('shop/{title}/{id}', 'ShopController@getProductShow')->name('shop.product');
	Route::get('shop/autocomplete', 'ShopController@autocomplete')->name('shop.autocomplete');
	Route::get('shop', 'ShopController@index')->name('shop.index');

    /*
    |--------------------------------------------------------------------------
    | Document Routes
    |--------------------------------------------------------------------------
    */
    Route::get('document/express-post', 'Home\DocumentController@expressPost')->name('home.document.express-post');
    Route::get('document/takeover', 'Home\DocumentController@takeover')->name('home.document.takeover');
    Route::get('document/pdf', 'Home\DocumentController@pdf')->name('home.document.pdf');
    Route::get('document/draft', 'Document\DraftController@index')->name('document.draft.index');
    Route::post('document/draft/choose', 'Document\DraftController@choose')->name('document.draft.choose');
    Route::post('document/draft/complete', 'Document\DraftController@complete')->name('document.draft.complete');
    Route::put('document/close', 'Document\CloseController@close')->name('document.close');
    Route::post('document/status', 'Document\StatusController@change')->name('document.status.change');
    Route::get('document/gratis', 'Document\GratisProductController@index')->name('document.gratis');
    Route::post('document/gratis/process', 'Document\GratisProductController@process')->name('document.gratis.process');
    Route::resource('document', 'DocumentController', ['parameters' => ['document' => 'id']]);
    Route::post('document/{id}/open', 'Document\OpenController@open')->name('document.open');
    Route::post('document/{id}/copy', 'Document\CopyController@copy')->name('document.copy');
    Route::post('document/{id}/reverse', 'Document\ReverseController@reverse')->name('document.reverse');
    Route::get('document/{id}/changes', 'Document\ChangeController@index')->name('document.changes.index');
    Route::post('document/{id}/changes', 'Document\ChangeController@store')->name('document.changes.store');
    Route::get('document/{id}/product', 'Document\ProductController@show')->name('document.product.show');
    Route::post('document/{id}/product', 'Document\ProductController@add')->name('document.product.add');
    Route::get('document/{id}/gratis', 'Document\GratisProductController@show')->name('document.gratis.product');
    Route::get('document/{id}/express-post/pdf', 'Document\ExpressPostController@pdf')->name('document.express-post.pdf');
    Route::get('document/{id}/track', 'Document\TrackController@show')->name('document.track.show');
    Route::get('document/{id}/shipping', 'Document\ShippingDataController@edit')->name('document.shipping.edit');
    Route::put('document/{id}/shipping', 'Document\ShippingDataController@update')->name('document.shipping.update');
	
    /*
    |--------------------------------------------------------------------------
    | Log Routes
    |--------------------------------------------------------------------------
    */
	Route::get('log/synced', 'LogController@synced')->name('log.synced');
	Route::get('log/for-sync', 'LogController@forSync')->name('log.for-sync');
	Route::get('log/{id}', 'LogController@show')->name('log.show');
	Route::get('log', 'LogController@index')->name('log.index');
    
    /*
    |--------------------------------------------------------------------------
    | Cart Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', 'CartController@index')->name('cart.index');
    Route::get('/cart/quick-overview', 'CartController@quickEstimateOverview')->name('cart.quick_overview');
    Route::post('/cart/add/{id}/{qty}', 'CartController@add');
    Route::post('/cart/update/{id}/{qty}', 'CartController@update');
    Route::post('/cart/remove/{id}', 'CartController@remove');
    Route::post('/cart/finish', 'CartController@finish');
   
    /*
    |--------------------------------------------------------------------------
    | Person Routes
    |--------------------------------------------------------------------------
    */
    Route::get('person/search', 'PersonController@search')->name('person.search');
    Route::resource('person', 'PersonController', ['parameters' => ['person' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Clients Routes
    |--------------------------------------------------------------------------
    */
    Route::get('client/search', 'ClientController@search')->name('client.search');
    Route::post('client/status', 'Client\StatusController@change')->name('client.status.change');
    Route::resource('client', 'ClientController', ['parameters' => ['client' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Location Routes
    |--------------------------------------------------------------------------
    */
    Route::get('location/picker', 'LocationController@picker')->name('location.picker');
    
    /*
    |--------------------------------------------------------------------------
    | Route Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'route'], function () {
        Route::get('rank', 'RouteController@rank')->name('route.rank');
        Route::get('person/{id}', 'Route\PersonController@index')->name('route.person.index');
        Route::get('person/{id}/details', 'Route\PersonController@details')->name('route.person.details');
        Route::put('person/{id}/update', 'Route\PersonController@update')->name('route.person.update');
        Route::put('person/{id}/assign', 'Route\PersonController@assign')->name('route.person.assign');
    });
    Route::delete('route/{id}', 'RouteController@destroy')->name('route.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | Contract Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'contract/{id}'], function () {
        Route::get('products', 'Contract\ProductController@edit')->name('contract.products');
        Route::post('products', 'Contract\ProductController@update');
    });
    Route::resource('contract', 'ContractController', ['parameters' => ['contract' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Action Routes
    |--------------------------------------------------------------------------
    */
    Route::get('action/stats', 'Action\StatsController@show')->name('action.stats');
    Route::group(['prefix' => 'action/{id}'], function () {
        Route::get('products', 'Action\ProductController@edit')->name('action.products');
        Route::post('products', 'Action\ProductController@update');
    });
    Route::group(['prefix' => 'action/{id}'], function () {
        Route::get('quantity', 'Action\CartController@quantity')->name('action.quantity');
        Route::post('add', 'Action\CartController@add')->name('action.cart');
    });
    Route::get('action/search', 'ActionController@search')->name('action.search');
    Route::resource('action', 'ActionController', ['parameters' => ['action' => 'id']]);
	
    /*
    |--------------------------------------------------------------------------
    | City Routes
    |--------------------------------------------------------------------------
    */
    Route::get('city/search', 'CityController@search')->name('city.search');
    Route::resource('city', 'CityController', ['parameters' => ['city' => 'id']]);
	
    /*
    |--------------------------------------------------------------------------
    | ExpressPost Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'express-post'], function () {
        Route::get('index', 'ExpressPost\ExpressPostController@index')->name('expresspost.index');
        Route::get('test', 'ExpressPost\ExpressPostController@test')->name('expresspost.test');
        Route::get('status/{id}', 'ExpressPost\ExpressPostController@status')->name('expresspost.status');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'payment/{id}'], function () {
        Route::post('confirm', 'PaymentController@confirm')->name('payment.confirm');
    });
    Route::resource('payment', 'PaymentController', ['parameters' => ['payment' => 'id']]);
    
    /*
    |--------------------------------------------------------------------------
    | Billing Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('billing/import', 'Billing\ImportController@import')->name('billing.import');
    Route::get('billing', 'BillingController@index')->name('billing.index');
    
    /*
    |--------------------------------------------------------------------------
    | Demand Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('demand/import', 'Demand\ImportController@import')->name('demand.import');
    Route::get('demand', 'DemandController@index')->name('demand.index');
    
    /*
    |--------------------------------------------------------------------------
    | Order Routes
    |--------------------------------------------------------------------------
    */
    Route::get('orders/test', 'OrderController@test')->name('orders.test');
    
    /*
    |--------------------------------------------------------------------------
    | Artisan Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'artisan'], function () {
        Route::get('cache/clear', 'ArtisanController@cacheClear')->name('artisan.cache.clear');
        Route::get('view/clear', 'ArtisanController@viewClear')->name('artisan.view.clear');
        Route::get('config/clear', 'ArtisanController@configClear')->name('artisan.config.clear');
        Route::get('route/clear', 'ArtisanController@routeClear')->name('artisan.route.clear');
        Route::get('migrate', 'ArtisanController@migrate')->name('artisan.migrate');
        Route::get('opcache/reset', 'ArtisanController@opcacheReset')->name('artisan.opcache.reset');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Email Preview Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'email'], function() {
        Route::get('/error', 'EmailController@errorMail');
        Route::get('/password-reset', 'EmailController@passwordReset');
        Route::get('/invite-user', 'EmailController@inviteUser');
        Route::get('/document-track', 'EmailController@documentTrack');
    });
});

/*
 * Cron
 */
Route::group(['prefix' => 'cron', 'namespace' => 'Cron', 'middleware' => 'no.index'], function() {
    Route::get('express-post-status', 'ExpressPostController@status')->name('cron.express-post.status');
    // Route::get('product-quantities', 'DocumentController@productQuantities')->name('cron.document.product_quantities');
    // Route::get('document-total', 'DocumentController@documentTotal')->name('cron.document.total');
});

/*
 * Track
 */
Route::group(['prefix' => 'track', 'namespace' => 'Track', 'middleware' => 'no.index'], function() {
    Route::get('d/{hash}/{id}', 'DocumentController@show')->name('track.document.show');
    Route::get('c/{hash}/{id}', 'ClientController@show')->name('track.client.show');
});

/*
 * Luceed
 */
Route::group(['prefix' => 'luceed', 'namespace' => 'Luceed', 'middleware' => 'no.index'], function() {
    Route::get('product/{id}', 'ProductController@create');
    Route::get('product/code/{code}', 'ProductController@code');
    Route::get('client/{id}', 'ClientController@create');
    Route::get('client/code/{code}', 'ClientController@code');
    Route::get('document-product/{id}', 'DocumentProductController@create');
    Route::get('document/{id}', 'DocumentController@create');
    Route::get('document/id/{id}', 'DocumentController@id');
});
