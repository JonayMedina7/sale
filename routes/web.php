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

Route::group(['middleware' =>['guest']], function()
{
	Route::get('/', 'Auth\LoginController@showLoginForm')->name('main'); 
	Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' =>['auth']], function()
{
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

	Route::get('welcome', function () {
    return view('welcome');
	});
	route::get('/dashboard', 'DashboardController');


	Route::group(['middleware' =>['storer']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/categoria', 'CategoryController@index');
		route::post('/categoria/registrar', 'CategoryController@store');
		route::put('/categoria/actualizar', 'CategoryController@update');
		route::put('/categoria/desactivar', 'CategoryController@desactive');
		route::put('/categoria/activar', 'CategoryController@active');
		route::get('/categoria/categorySelect', 'CategoryController@categorySelect');

		route::get('/product', 'ProductController@index');
		route::post('/product/register', 'ProductController@store');
		route::put('/product/update', 'ProductController@update');
		route::put('/product/desactive', 'ProductController@desactive');
		route::put('/product/active', 'ProductController@active');
		route::get('/product/productSearch', 'ProductController@productSearch');
		route::get('/product/listProduct', 'ProductController@listProduct');
		route::get('/product/listPdf', 'ProductController@listPdf')->name('products_pdf');

		route::get('/provider', 'ProviderController@index');
		route::post('/provider/register', 'ProviderController@store');
		route::put('/provider/update', 'ProviderController@update');
		route::put('/provider/desactive', 'ProviderController@desactive');
		route::put('/provider/active', 'ProviderController@active');
		route::get('/provider/providerSelect', 'ProviderController@providerSelect');


		route::get('/purchase', 'PurchaseController@index');
		route::post('/purchase/register', 'PurchaseController@store');
		route::put('/purchase/update', 'PurchaseController@update');
		route::put('/purchase/desactive', 'PurchaseController@desactive');
		route::get('/purchase/getHeader', 'PurchaseController@getHeader');
		route::get('/purchase/getDetail', 'PurchaseController@getDetail'); 

	});

	Route::group(['middleware' =>['seller']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/client', 'ClientController@index');
		route::post('/client/register', 'ClientController@store');
		route::put('/client/update', 'ClientController@update');
		route::get('/client/clientSelect', 'ClientController@clientSelect');

		route::get('/sale', 'SaleController@index');
		route::get('/sale/saleId', 'SaleController@saleId');
		route::post('/sale/register', 'SaleController@store');
		route::put('/sale/update', 'SaleController@update');
		route::put('/sale/desactive', 'SaleController@desactive');
		route::get('/sale/getHeader', 'SaleController@getHeader');
		route::get('/sale/getDetail', 'SaleController@getDetail'); 
		route::get('/sale/pdf/{id}', 'SaleController@pdf')->name('venta_pdf');
		route::get('/sale/pdfw/{id}', 'SaleController@pdfw')->name('venta'); 

		route::get('/product/listProductSale', 'ProductController@listProductSale');
		route::get('/product/productSearchSale', 'ProductController@productSearchSale');


	});

	Route::group(['middleware' =>['admin']], function()
	{
		Route::get('/home', 'HomeController@index')->name('home');

		route::get('/categoria', 'CategoryController@index');
		route::post('/categoria/registrar', 'CategoryController@store');
		route::put('/categoria/actualizar', 'CategoryController@update');
		route::put('/categoria/desactivar', 'CategoryController@desactive');
		route::put('/categoria/activar', 'CategoryController@active');
		route::get('/categoria/categorySelect', 'CategoryController@categorySelect');

		route::get('/product', 'ProductController@index');
		route::post('/product/register', 'ProductController@store');
		route::put('/product/update', 'ProductController@update');
		route::put('/product/desactive', 'ProductController@desactive');
		route::put('/product/active', 'ProductController@active');
		route::get('/product/listProduct', 'ProductController@listProduct');
		route::get('/product/productSearchSale', 'ProductController@productSearchSale');
		route::get('/product/listProductSale', 'ProductController@listProductSale');
		route::get('/product/listPdf', 'ProductController@listPdf')->name('products_pdf'); 


		route::get('/client', 'ClientController@index');
		route::post('/client/register', 'ClientController@store');
		route::put('/client/update', 'ClientController@update');
		route::put('/client/desactive', 'ClientController@desactive');
		route::put('/client/active', 'ClientController@active');
		route::get('/client/clientSelect', 'ClientController@clientSelect');

		route::get('/provider', 'ProviderController@index');
		route::post('/provider/register', 'ProviderController@store');
		route::put('/provider/update', 'ProviderController@update');
		route::put('/provider/desactive', 'ProviderController@desactive');
		route::put('/provider/active', 'ProviderController@active');
		route::get('/provider/providerSelect', 'ProviderController@providerSelect');


		route::get('/role', 'RoleController@index');
		route::post('/role/register', 'RoleController@store');
		route::put('/role/update', 'RoleController@update');
		route::put('/role/desactive', 'RoleController@desactive');
		route::put('/role/active', 'RoleController@active');
		route::get('/role/selectRole', 'RoleController@selectRole');

		route::get('/user', 'UserController@index');
		route::post('/user/register', 'UserController@store');
		route::put('/user/update', 'UserController@update');
		route::put('/user/desactive', 'UserController@desactive');
		route::put('/user/active', 'UserController@active');

		route::get('/purchase', 'PurchaseController@index');
		route::post('/purchase/register', 'PurchaseController@store');
		route::put('/purchase/update', 'PurchaseController@update');
		route::put('/purchase/desactive', 'PurchaseController@desactive');
		route::get('/purchase/getHeader', 'PurchaseController@getHeader');
		route::get('/purchase/getDetail', 'PurchaseController@getDetail'); 

		route::get('/sale', 'SaleController@index');
		route::post('/sale/register', 'SaleController@store');
		route::put('/sale/update', 'SaleController@update');
		route::put('/sale/desactive', 'SaleController@desactive');
		route::get('/sale/getHeader', 'SaleController@getHeader');
		route::get('/sale/getDetail', 'SaleController@getDetail');
		route::get('/sale/saleId', 'SaleController@saleId'); 


	});
	


});
