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

Route::get('/', [
    'as' => 'login.index',
    'uses' => 'Auth\LoginController@index'
]);

Route::get('login/redirect/ya', 'Auth\LoginController@yahooRedirect');
Route::get('login/callback/ya', 'Auth\LoginController@yahooCallback');
