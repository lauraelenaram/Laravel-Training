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

Route::group(['middleware' => ['guest']],function()
{
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']],function()
{
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('content/content');
    }) -> name('main');

    Route::group(['middleware' => ['Almacenero']],function()
    {
        Route::get('/categories','CategoryController@index');
        Route::post('/categories/register','CategoryController@store');
        Route::put('/categories/update','CategoryController@update');
        Route::put('/categories/update/update_condition','CategoryController@update_condition');
        Route::get('/categories/selectCategory','CategoryController@selectCategory');

        Route::get('/articles','ArticleController@index');
        Route::post('/articles/register','ArticleController@store');
        Route::put('/articles/update','ArticleController@update');
        Route::put('/articles/update/update_condition','ArticleController@update_condition');

        Route::get('/suppliers','SupplierController@index');
        Route::post('/suppliers/register','SupplierController@store');
        Route::put('/suppliers/update','SupplierController@update');
        Route::get('/suppliers/selectSupplier','SupplierController@selectSupplier');

        Route::get('/incomes','IncomeController@index');
        Route::post('/incomes/register','IncomeController@store');
        Route::put('/incomes/desactivate','IncomeController@desactivate');
    });

    Route::group(['middleware' => ['Vendedor']],function()
    {
        Route::get('/clients','ClientController@index');
        Route::post('/clients/register','ClientController@store');
        Route::put('/clients/update','ClientController@update');
    });

    Route::group(['middleware' => ['Administrador']],function()
    {
        Route::get('/clients','ClientController@index');
        Route::post('/clients/register','ClientController@store');
        Route::put('/clients/update','ClientController@update');

        Route::get('/categories','CategoryController@index');
        Route::post('/categories/register','CategoryController@store');
        Route::put('/categories/update','CategoryController@update');
        Route::put('/categories/update/update_condition','CategoryController@update_condition');
        Route::get('/categories/selectCategory','CategoryController@selectCategory');

        Route::get('/articles','ArticleController@index');
        Route::post('/articles/register','ArticleController@store');
        Route::put('/articles/update','ArticleController@update');
        Route::put('/articles/update/update_condition','ArticleController@update_condition');

        Route::get('/suppliers','SupplierController@index');
        Route::post('/suppliers/register','SupplierController@store');
        Route::put('/suppliers/update','SupplierController@update');
        Route::get('/suppliers/selectSupplier','SupplierController@selectSupplier');

        Route::get('/roles','RolController@index');
        Route::get('/roles/selectRol','RolController@selectRol');
        
        Route::get('/users','UserController@index');
        Route::post('/users/register','UserController@store');
        Route::put('/users/update','UserController@update');
        Route::put('/users/update/update_condition','UserController@update_condition');

        Route::get('/incomes','IncomeController@index');
        Route::post('/incomes/register','IncomeController@store');
        Route::put('/incomes/desactivate','IncomeController@desactivate');
    });
    
});

//Route::get('/home', 'HomeController@index')->name('home');
