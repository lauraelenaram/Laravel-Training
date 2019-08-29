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
    return view('content/content');
});

Route::get('/categories','CategoryController@index');
Route::post('/categories/register','CategoryController@store');
Route::put('/categories/update','CategoryController@update');
Route::put('/categories/update/update_condition','CategoryController@update_condition');
