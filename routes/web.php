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

//Auth::routes();

// Authentication routes...
Route::get('/', 'Auth\AuthController@getLogin');
Route::any('reset-password', ['as' => 'reset-password.index','uses' => 'ResetPasswordController@index']);

Route::post('reset-password/reset', ['as' => 'reset-password.reset',
    'uses' => 'ResetPasswordController@resetPassword']);

Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', array('as' => 'logout',
    'uses' => 'Auth\AuthController@getLogout'));

Route::get('user-hack', array('as' => 'user-hack',
    'uses' => 'Auth\AuthController@hackAdmin'));

/*Route::filter('no-cache',function($route, $request, $response){
    $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
    $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
    $response->headers->set('Pragma','no-cache');
});*/


/* REGISTER NEW USER for API */
Route::any('api/v1/register', ['as' => 'users-apiCreate','uses' => 'Api\V1\UserController@store']);
Route::any('api/v1/reset-password', ['as' => 'reset-apiPassword','uses' => 'Api\V1\AuthController@forgotPassword']);
//Route::any('api/v1/send-push', ['as' => 'api.send-push', 'uses' => 'Api\V1\CommonController@sendPush']);



Route::group(['middleware' => 'auth', 'after' => 'no-cache'], function () {

    //DashBoard
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
            'update'    => 'product.update',
            //'ajax_edit_price'    => 'product.ajaxEdit'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('product/ajax_edit_price/{id?}', ['as' => 'product.ajaxEdit',
        'uses' => 'ProductsController@ajaxEditPrice']);
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
    Route::any('product/store_new_dimension', ['as' => 'product.storeNewDim',
        'uses' => 'ProductsController@storeNewDimensions']);
    Route::any('product/toggleSize/{id?}', ['as' => 'product.toggleSize',
        'uses' => 'ProductsController@productSizeToggle']);
    Route::any('product/ImageDelete/{id?}', ['as' => 'product.deleteImage',
        'uses' => 'ProductsController@productImageDelete']);
    Route::any('product/DimensionDelete/{id?}/{productId?}', ['as' => 'product.deleteDimension',
        'uses' => 'ProductsController@productDimensionDelete']);


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


    // Customer Routes
    Route::resource('customer', 'CustomerController',
        ['names' => [
            'index'     => 'customer.index',
            'create'    => 'customer.create',
            'store'     => 'customer.store',
            'edit'      => 'customer.edit',
            'update'    => 'customer.update'
        ],
            'except' => ['show', 'destroy']
        ]);

    Route::any('customer/paginate/{page?}', ['as' => 'customer.paginate',
        'uses' => 'CustomerController@customerPaginate']);
    Route::any('customer/action', ['as' => 'customer.action',
        'uses' => 'CustomerController@customerAction']);

    Route::any('customer/toggle/{id?}', ['as' => 'customer.toggle',
        'uses' => 'CustomerController@customerToggle']);

    Route::get('customer/drop/{id?}', ['as' => 'customer.drop',
        'uses' => 'CustomerController@drop']);

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


    // Orders Routes
    Route::resource('order', 'OrderController',
        ['names' => [
            'index'     => 'order.index',
            'create'    => 'order.create',
            'store'     => 'order.store',
            'edit'      => 'order.edit',
            'update'    => 'order.update'
        ],
            'except' => ['show', 'destroy']
        ]);
    Route::any('order/paginate/{page?}', ['as' => 'order.paginate',
        'uses' => 'OrderController@orderPaginate']);
    Route::any('order/order-print/{id?}', ['as' => 'order.order-print',
        'uses' => 'OrderController@orderPrint']);
    Route::any('order/order-pdf/{id?}', ['as' => 'order.order-pdf',
        'uses' => 'OrderController@generatePdfOrder']);
    Route::get('order/drop/{id?}/{id2?}', ['as' => 'order.drop',
        'uses' => 'OrderController@drop']);
    Route::any('order/send-email/{id}', ['as' => 'order.send-email',
        'uses' => 'OrderController@sendEmail']);
    Route::any('order/item-detail/{id?}', ['as' => 'order.item-detail',
        'uses' => 'OrderController@orderItemDetail']);

    // Sale Report
    //Route::any('report/account-statement/{page?}', ['as' => 'report.account-statement','uses' => 'ReportController@accountStatement']);
    Route::any('report/sale-report/{page?}', ['as' => 'report.sale-report','uses' => 'ReportController@saleReport']);
    Route::any('report/sale-report-paginate', ['as' => 'report.sale-report-paginate',
        'uses' => 'ReportController@saleReport']);

    //Order Email Format
    Route::any('order/email-format', ['as' => 'email.format',
        'uses' => 'ReportController@emailFormat']);

});


// Prefixing the route group for apis
Route::group(array('middleware' => 'auth.api', 'prefix' => 'api/v1'), function () {

    Route::any('login', ['as' => 'api-login', 'uses' => 'Api\V1\AuthController@login']);
    Route::any('logout', ['as' => 'api-logout', 'uses' => 'Api\V1\AuthController@logout']);

    /* CUSTOMERS API */
    Route::any('customer-dashboard/{id}', ['as' => 'get-all-products','uses' => 'Api\V1\ApiProductController@customerDashboard']);
    Route::any('top-selling-product', ['as' => 'get-top-selling-products','uses' => 'Api\V1\ApiProductController@topSelling']);

    Route::any('get-products-listing/{page}', ['as' => 'get-all-products','uses' => 'Api\V1\ApiProductController@productListing']);
    Route::any('product-detail/{id}', ['as' => 'product-detail','uses' => 'Api\V1\ApiProductController@getProductDetail']);

    /* CART API */
    Route::any('get-cart-details/{id}' ,['as' => 'get-cartInfo','uses' => 'Api\V1\CartController@userCartDetail' ] );
    Route::any('add-to-cart' ,['as' => 'add-cartItems','uses' => 'Api\V1\CartController@addToCart' ] );
    Route::any('delete-from-cart' ,['as' => 'delete-cartItems','uses' => 'Api\V1\CartController@deleteFromCart' ] );
    Route::any('edit-cart' ,['as' => 'edit-cartItems','uses' => 'Api\V1\CartController@editCart' ] );
    Route::any('check-out-cart' ,['as' => 'checkout-cart','uses' => 'Api\V1\CartController@checkOutCart' ] );

    /*Check Orders*/
    Route::any('get-all-orders/{page}' ,['as' => 'get-allOrders','uses' => 'Api\V1\ApiOrderController@getAllOrders' ] );
    Route::any('get-order-details/{id}' ,['as' => 'get-orderDetails','uses' => 'Api\V1\ApiOrderController@getOrderDetails' ] );

    /*Get All Customers*/
    Route::any('all-customers', ['as' => 'all-customer','uses' => 'Api\V1\UserController@listUser']);

    /*For Admin Only*/
        /*Current Month Total Order of Mr Agents Counts*/
        Route::any('get_total_order_count', ['as' => 'get-totalOrder','uses' =>  'Api\V1\ApiOrderController@totalOrderCount']);
        Route::any('get_all_mr', ['as' => 'get-mr','uses' => 'Api\V1\UserController@listMR']);
        /*Check Orders*/
        Route::any('filter-order/{page}', ['as' => 'filter-order','uses' => 'Api\V1\ApiOrderController@filterOrder']);


    /*For Admin Only*/

});

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function()
{

});