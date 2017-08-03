<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.login');
});





/* REGISTER NEW USER for API */
Route::any('api/v1/register', ['as' => 'users-apiCreate','uses' => 'Api\V1\UserController@store']);
Route::any('api/v1/reset-password', ['as' => 'reset-apiPassword','uses' => 'Api\V1\AuthController@forgotPassword']);
//Route::any('api/v1/send-push', ['as' => 'api.send-push', 'uses' => 'Api\V1\CommonController@sendPush']);

Auth::routes();

Route::group(['middleware' => 'auth', 'after' => 'no-cache'], function () {

    Route::any('dashboard', 'HomeController@index')->name('home');

    // Setting Routes
    Route::any('myaccount', ['as' => 'setting.manage-account',
        'uses' => 'SettingController@myAccount']);

    // Hsn Routes
    Route::resource('hsn', 'HsnController',
        ['names' => [
            'index'     => 'hsn.index',
            'create'    => 'hsn.create',
            'store'     => 'hsn.store',
            'edit'      => 'hsn.edit',
            'update'    => 'hsn.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('hsn/paginate/{page?}', ['as' => 'hsn.paginate',
        'uses' => 'HsnController@hsnPaginate']);
    Route::any('hsn/action', ['as' => 'hsn.action',
        'uses' => 'HsnController@hsnAction']);
    Route::any('hsn/toggle/{id?}', ['as' => 'hsn.toggle',
        'uses' => 'HsnController@hsnToggle']);

    // Size Routes
    Route::resource('size', 'SizeController',
        ['names' => [
            'index'     => 'size.index',
            'create'    => 'size.create',
            'store'     => 'size.store',
            'edit'      => 'size.edit',
            'update'    => 'size.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('size/paginate/{page?}', ['as' => 'size.paginate',
        'uses' => 'SizeController@sizePaginate']);
    Route::any('size/action', ['as' => 'size.action',
        'uses' => 'SizeController@sizeAction']);
    Route::any('size/toggle/{id?}', ['as' => 'size.toggle',
        'uses' => 'SizeController@sizeToggle']);
    Route::any('size/drop/{id?}', ['as' => 'size.drop',
        'uses' => 'SizeController@drop']);

    /*
     * Financial Year route
     */
    Route::resource('financial-year','FinancialYearController', [
        'names' => [
            'index' => 'financial-year.index',
            'create' => 'financial-year.create',
            'store' => 'financial-year.store',
            'edit' => 'financial-year.edit',
            'update' => 'financial-year.update',
        ],
        'except' => ['show','destroy']
    ]);
    Route::any('financial-year/paginate/{page?}', ['as' => 'financial-year.paginate',
        'uses' => 'FinancialYearController@financialYearPaginate']);
    Route::any('financial-year/action', ['as' => 'financial-year.action',
        'uses' => 'FinancialYearController@action']);
    Route::any('financial-year/toggle/{id?}', ['as' => 'financial-year.toggle',
        'uses' => 'FinancialYearController@financialYearToggle']);
    Route::any('financial-year/drop/{id?}', ['as' => 'financial-year.drop',
        'uses' => 'FinancialYearController@drop']);

    // Tax Routes
    Route::resource('tax', 'TaxController',
        ['names' => [
            'index'     => 'tax.index',
            'create'    => 'tax.create',
            'store'     => 'tax.store',
            'edit'      => 'tax.edit',
            'update'    => 'tax.update'
        ],
            'except' => ['show', 'destroy']
        ]);

    Route::any('tax/paginate/{page?}', ['as' => 'tax.paginate',
        'uses' => 'TaxController@taxPaginate']);
    Route::any('tax/action', ['as' => 'tax.action',
        'uses' => 'TaxController@taxAction']);
    Route::any('tax/toggle/{id?}', ['as' => 'tax.toggle',
        'uses' => 'TaxController@taxToggle']);
    Route::get('tax/drop/{id?}', ['as' => 'tax.drop']);

    // Product Routes
    Route::resource('product', 'ProductsController',
        ['names' => [
            'index'     => 'product.index',
            'create'    => 'product.create',
            'store'     => 'product.store',
            'edit'      => 'product.edit',
            'update'    => 'product.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('product/paginate/{page?}', ['as' => 'product.paginate',
        'uses' => 'ProductsController@productPaginate']);
    Route::any('product/action', ['as' => 'product.action',
        'uses' => 'ProductsController@productAction']);
    Route::any('product/toggle/{id?}', ['as' => 'product.toggle',
        'uses' => 'ProductsController@productToggle']);
    Route::any('product/storeSize/{id?}', ['as' => 'product.storeSize',
        'uses' => 'ProductsController@storeSize']);
    Route::any('product/storeDimValue', ['as' => 'product.storeDimValue',
        'uses' => 'ProductsController@storeDimensionValue']);
    Route::any('product/toggleSize/{id?}', ['as' => 'product.toggleSize',
        'uses' => 'ProductsController@productSizeToggle']);
    Route::any('product/ImageDelete/{id?}', ['as' => 'product.deleteImage',
        'uses' => 'ProductsController@productImageDelete']);


    // Company Routes
    Route::resource('company', 'CompanyController',
        ['names' => [
            'index'     => 'company.index',
            'create'    => 'company.create',
            'store'     => 'company.store',
            'edit'      => 'company.edit',
            'update'    => 'company.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('company/paginate/{page?}', ['as' => 'company.paginate',
        'uses' => 'CompanyController@companyPaginate']);
    Route::any('company/action', ['as' => 'company.action',
        'uses' => 'CompanyController@companyAction']);
    Route::any('company/toggle/{id?}', ['as' => 'company.toggle',
        'uses' => 'CompanyController@companyToggle']);
    Route::any('company/add-bank/{id?}', ['as' => 'company.add-bank',
        'uses' => 'CompanyController@companyAddBank']);
    Route::any('company/edit-bank/{id?}', ['as' => 'company.edit-bank',
        'uses' => 'CompanyController@companyEditBank']);
    Route::any('company/update-bank/{id?}', ['as' => '  company.update-bank',
        'uses' => 'CompanyController@companyUpdateBank']);
    Route::get('company/drop/{id?}', ['as' => 'company.drop',
        'uses' => 'CompanyController@drop']);

    // Role Routes
    Route::resource('role', 'RoleController',
        ['names' => [
            'index'     => 'role.index',
            'create'    => 'role.create',
            'store'     => 'role.store',
            'edit'      => 'role.edit',
            'update'    => 'role.update'
        ],
            'except' => ['show', 'destroy']
        ]);

    Route::any('role/paginate/{page?}', ['as' => 'role.paginate',
        'uses' => 'RoleController@rolePaginate']);
    Route::any('role/action', ['as' => 'role.action',
        'uses' => 'RoleController@roleAction']);
    Route::any('role/toggle/{id?}', ['as' => 'role.toggle',
        'uses' => 'RoleController@roleToggle']);
    Route::get('role/drop/{id?}', ['as' => 'role.drop',
        'uses' => 'RoleController@drop']);

    // User Routes
    Route::resource('user', 'UserController',
        ['names' => [
            'index'     => 'user.index',
            'create'    => 'user.create',
            'store'     => 'user.store',
            'edit'      => 'user.edit',
            'update'    => 'user.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('user/paginate/{page?}', ['as' => 'user.paginate',
        'uses' => 'UserController@userPaginate']);
    Route::any('user/action', ['as' => 'user.action',
        'uses' => 'UserController@userAction']);
    Route::any('user/toggle/{id?}', ['as' => 'user.toggle',
        'uses' => 'UserController@userToggle']);
    Route::get('user/drop/{id?}', ['as' => 'user.drop',
        'uses' => 'UserController@drop']);


    // Menu Routes
    Route::resource('menu', 'MenuController',
        ['names' => [
            'index'     => 'menu.index',
            'create'    => 'menu.create',
            'store'     => 'menu.store',
            'edit'      => 'menu.edit',
            'update'    => 'menu.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('menu/action', ['as' => 'menu.action','uses' => 'MenuController@menuAction']);
    Route::any('menu/paginate/{page?}', ['as' => 'menu.paginate','uses' => 'MenuController@menuPaginate']);
    Route::any('menu/sorter/{page?}', ['as' => 'menu.sorter','uses' => 'MenuController@sortingMenu']);
    Route::any('menu/toggle/{id?}', ['as' => 'menu.toggle','uses' => 'MenuController@menuToggle']);

});


// Prefixing the route group for apis
Route::group(array('middleware' => 'auth.api', 'prefix' => 'api/v1'), function () {

    Route::any('login', ['as' => 'api-login', 'uses' => 'Api\V1\AuthController@login']);
    Route::any('logout', ['as' => 'api-logout', 'uses' => 'Api\V1\AuthController@logout']);

    /* CUSTOMERS API */
    Route::any('customer-dashboard/{id}', ['as' => 'get-all-products','uses' => 'Api\V1\ApiProductController@customerDashboard']);
    //Route::any('get-products', ['as' => 'get-all-products','uses' => 'Api\V1\ApiProductController@getProducts']);
    Route::any('product-detail/{id}', ['as' => 'product-detail','uses' => 'Api\V1\ApiProductController@getProductDetail']);

    /* CART API */
    Route::any('get-cart-details' ,['as' => 'get-cartInfo','uses' => 'Api\V1\CartController@userCartDetail' ] );
    Route::any('add-to-cart' ,['as' => 'add-cartItems','uses' => 'Api\V1\CartController@addToCart' ] );
    Route::any('delete-from-cart' ,['as' => 'delete-cartItems','uses' => 'Api\V1\CartController@deleteFromCart' ] );

    //Route::any('product-detail/{id}', ['as' => 'product-detail','uses' => 'Api\V1\ApiProductController@getProductDetail']);


});

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function()
{

});