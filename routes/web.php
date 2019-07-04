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
    'uses' => 'Frontend\Auth\LoginController@index'
]);

Route::get('login/redirect/ya', 'Frontend\Auth\LoginController@yahooRedirect');
Route::get('login/callback/ya', 'Frontend\Auth\LoginController@yahooCallback');

Route::get('login/redirect/line', 'Frontend\Auth\LoginController@lineRedirect');
Route::get('login/callback/line', 'Frontend\Auth\LoginController@lineCallback');
