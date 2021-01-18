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

Route::get('/', 'Frontend\Auth\LoginController@getLogin');

Route::get('/login', [
    'as' => 'login.index',
    'uses' => 'Frontend\Auth\LoginController@getLogin'
]);

// Api yahoo
Route::get('login/redirect/ya', 'Frontend\Auth\LoginController@yahooRedirect');
Route::get('login/callback/ya', 'Frontend\Auth\LoginController@yahooCallback');

// Api line
Route::get('login/redirect/line', 'Frontend\Auth\LoginController@lineRedirect');
Route::get('login/callback/line', 'Frontend\Auth\LoginController@lineCallback');

// Api zalo
Route::get('login/redirect/zalo', 'Frontend\Auth\LoginController@zaloRedirect');
Route::get('login/callback/zalo', 'Frontend\Auth\LoginController@zaloCallback');

// Socialite: facebook, google, twitter, github, gitlab
Route::get('login/redirect/{social}', 'Frontend\Auth\LoginController@redirect');
Route::get('login/callback/{social}', 'Frontend\Auth\LoginController@callback');
