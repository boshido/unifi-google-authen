<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
date_default_timezone_set("Asia/Bangkok");

Route::controller('admin', 'AdminController');
Route::controller('unifi', 'UnifiController');
Route::controller('guest', 'GuestController');
Route::get('/', function()
{
	return Redirect::action('GuestController@getSignin');
});

Route::filter('auth', function()
{	
	if(!Session::has('refresh_token')){
		$cookie = Cookie::get('refresh_token');
		Session::put('refresh_token',$cookie);	
	}
	if(!Session::has('id')){
		$cookie = Cookie::get('id');
		Session::put('id',$cookie);	
	}
});

Route::filter('management', function()
{	
	if(!Session::has('login')){
		return 	Redirect::action('AdminController@getSignin');
	}
});