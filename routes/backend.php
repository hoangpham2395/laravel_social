<?php

Route::get('login', [
	'as' => 'backend.login.index',
	'uses' => 'Auth\LoginController@index'
]);

Route::resource('admin', 'AdminController');