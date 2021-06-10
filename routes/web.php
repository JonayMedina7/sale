<?php
Route::get('/clear-cacheter', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
Route::group(['middleware' =>['guest']], function()
{
	Route::get('/', 'Auth\LoginController@showLoginForm')->name('main');
	Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' =>['auth']], function()
{
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

	Route::get('welcome', function () {
    return view('welcome');	});

	route::get('/dashboard', 'DashboardController');

	route::get('welcomer', function (){
		return view('welcomer');
	});

	Route::group(['middleware' =>['storer']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/category', 'CategoryController@index');
		route::post('/category/register', 'CategoryController@store');
		route::post('/category/update', 'CategoryController@update');
		route::post('/category/desactive', 'CategoryController@desactive');
		route::post('/category/active', 'CategoryController@active');
		route::get('/category/categorySelect', 'CategoryController@categorySelect');

		route::get('/product', 'ProductController@index');
		route::post('/product/register', 'ProductController@store');
		route::post('/product/update', 'ProductController@update');
		route::post('/product/desactive', 'ProductController@desactive');
		route::post('/product/active', 'ProductController@active');
		route::get('/product/productSearch', 'ProductController@productSearch');
		route::get('/product/listProduct', 'ProductController@listProduct');
		route::get('/product/listPdf', 'ProductController@listPdf')->name('products_pdf');

		route::get('/provider', 'ProviderController@index');
		route::post('/provider/register', 'ProviderController@store');
		route::post('/provider/update', 'ProviderController@update');
		route::post('/provider/desactive', 'ProviderController@desactive');
		route::post('/provider/active', 'ProviderController@active');
		route::get('/provider/providerSelect', 'ProviderController@providerSelect');

		route::get('/purchase', 'PurchaseController@index');
		route::post('/purchase/register', 'PurchaseController@store');
		route::post('/purchase/update', 'PurchaseController@update');
		route::post('/purchase/desactive', 'PurchaseController@desactive');
		route::get('/purchase/getHeader', 'PurchaseController@getHeader');
		route::get('/purchase/getDetail', 'PurchaseController@getDetail');

	});

	Route::group(['middleware' =>['seller']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/client', 'ClientController@index');
		route::post('/client/register', 'ClientController@store');
		route::post('/client/update', 'ClientController@update');
		route::get('/client/clientSelect', 'ClientController@clientSelect');
		route::get('/client/clientSearch', 'ClientController@clientSearch');

		route::get('/sale', 'SaleController@index');
		route::get('/sale/saleId', 'SaleController@saleId');
		route::post('/sale/register', 'SaleController@store');
		route::post('/sale/update', 'SaleController@update');
		route::post('/sale/desactive', 'SaleController@desactive');
		route::get('/sale/getHeader', 'SaleController@getHeader');
		route::get('/sale/getDetail', 'SaleController@getDetail');
		route::get('/sale/pdf/{id}', 'SaleController@pdf')->name('venta_pdf');
		route::get('/sale/pdfw/{id}', 'SaleController@pdfw')->name('venta');

		route::get('/product/listProductSale', 'ProductController@listProductSale');
		route::get('/product/productSearchSale', 'ProductController@productSearchSale');

		route::get('/retencion', 'RetentionController@index');
	});

	Route::group(['middleware' =>['admin']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/category', 'CategoryController@index');
		route::post('/category/register', 'CategoryController@store');
		route::post('/category/update', 'CategoryController@update');
		route::post('/category/desactive', 'CategoryController@desactive');
		route::post('/category/active', 'CategoryController@active');
		route::get('/categoria/categorySelect', 'CategoryController@categorySelect');

		route::get('/product', 'ProductController@index');
		route::post('/product/register', 'ProductController@store');
		route::post('/product/update', 'ProductController@update');
		route::post('/product/desactive', 'ProductController@desactive');
		route::post('/product/active', 'ProductController@active');
		route::get('/product/listProduct', 'ProductController@listProduct');
		route::get('/product/productSearchSale', 'ProductController@productSearchSale');
		route::get('/product/listProductSale', 'ProductController@listProductSale');
		route::get('/product/listPdf', 'ProductController@listPdf')->name('products_pdf');


		route::get('/client', 'ClientController@index');
		route::post('/client/register', 'ClientController@store');
		route::post('/client/update', 'ClientController@update');
		route::post('/client/desactive', 'ClientController@desactive');
		route::post('/client/active', 'ClientController@active');
		route::get('/client/clientSelect', 'ClientController@clientSelect');
		route::get('/client/clientSearch', 'ClientController@clientSearch');

		route::get('/provider', 'ProviderController@index');
		route::post('/provider/register', 'ProviderController@store');
		route::post('/provider/update', 'ProviderController@update');
		route::post('/provider/desactive', 'ProviderController@desactive');
		route::post('/provider/active', 'ProviderController@active');
		route::get('/provider/providerSelect', 'ProviderController@providerSelect');


		route::get('/role', 'RoleController@index');
		route::post('/role/register', 'RoleController@store');
		route::post('/role/update', 'RoleController@update');
		route::post('/role/desactive', 'RoleController@desactive');
		route::post('/role/active', 'RoleController@active');
		route::get('/role/selectRole', 'RoleController@selectRole');

		route::get('/user', 'UserController@index');
		route::post('/user/register', 'UserController@store');
		route::post('/user/update', 'UserController@update');
		route::post('/user/desactive', 'UserController@desactive');
		route::post('/user/active', 'UserController@active');

		route::get('/purchase', 'PurchaseController@index');
		route::post('/purchase/register', 'PurchaseController@store');
		route::post('/purchase/update', 'PurchaseController@update');
		route::post('/purchase/desactive', 'PurchaseController@desactive');
		route::get('/purchase/getHeader', 'PurchaseController@getHeader');
		route::get('/purchase/getDetail', 'PurchaseController@getDetail');
		route::get('/purchase/purchaseRet', 'PurchaseController@purchaseRet');
		route::get('/purchase/listPurchaseRet', 'PurchaseController@listPurchaseRet');
		route::get('/purchase/search', 'PurchaseController@searchPurchase');

		Route::get('/purchase/credits', 'PurchaseController@indexCredit');
		Route::post('purchase/store-note', 'PurchaseController@storeNote');

		route::get('/sale', 'SaleController@index');
		route::post('/sale/register', 'SaleController@store');
		route::post('/sale/update', 'SaleController@update');
		route::post('/sale/desactive', 'SaleController@desactive');
		route::get('/sale/getHeader', 'SaleController@getHeader');
		route::get('/sale/getDetail', 'SaleController@getDetail');
		route::get('/sale/saleId', 'SaleController@saleId');
		route::get('/sale/saleSearchRet', 'SaleController@saleSearchRet');
		route::get('/sale/pdf/{id}', 'SaleController@pdf')->name('venta_pdf');
		route::get('/sale/pdfw/{id}', 'SaleController@pdfw')->name('venta');
		route::get('sale/email', 'SaleController@email');
		route::post('/sale/print', 'SaleController@print');

		route::get('/quota', 'QuotaController@index');
		route::post('/quota/register', 'QuotaController@store');
		route::post('/quota/update', 'QuotaController@update');
		route::post('/quota/desactive', 'QuotaController@desactive');
		route::get('/quota/getHeader', 'QuotaController@getHeader');
		route::get('/quota/getDetail', 'QuotaController@getDetail');
		route::get('/quota/quotaId', 'QuotaController@quotaId');
		route::get('/quota/pdf/{id}', 'QuotaController@pdf')->name('cotizacion_pdf');

		route::get('/retention', 'RetentionController@index');
		route::get('retention/retId', 'RetentionController@retId');
		route::post('/retention/register', 'RetentionController@store');
		route::get('/retention/getHeader', 'RetentionController@getHeader');
		route::get('/retention/getDetail', 'RetentionController@getDetail');
		route::post('/retention/update', 'RetentionController@update');
		route::post('/retention/desactive', 'RetentionController@desactive');
		route::get('/retention/getDownload', 'RetentionController@getDownload');
		route::get('/retention/pdf/{id}', 'RetentionController@pdf')->name('retencion');
		route::get('/retention/pdfw/{id}', 'RetentionController@pdfw')->name('retencion');
		route::get('/retention/email/{id}', 'RetentionController@email');

		route::get('/company', 'CompanyController@index');
		route::post('/company/register', 'CompanyController@store');
		route::post('/company/update', 'CompanyController@update');

		route::get('/tax', 'TaxController@index');
		route::post('/tax/register', 'TaxController@store');
		route::post('/tax/update', 'TaxController@update');
		route::get('tax/searchTax', 'TaxController@searchTax');

		route::get('/buy/indexb', 'PurchaseController@indexb');
		route::post('/buy/register', 'PurchaseController@storeb');
		route::post('/buy/update', 'PurchaseController@updateb');
		route::post('/buy/desactive', 'PurchaseController@desactiveb');
		route::get('/buy/getHeaderb', 'PurchaseController@getHeaderb');
		route::get('/buy/getDetail', 'PurchaseController@getDetail');
		route::get('/buy/purchaseRet', 'PurchaseController@purchaseRet');
		route::get('/buy/listPurchaseRet', 'PurchaseController@listPurchaseRet');


	});



});
