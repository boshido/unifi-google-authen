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

Route::controller('unifi', 'UnifiController');
Route::controller('guest', 'GuestController');
Route::get('/', function()
{
	return Redirect::action('GuestController@getSignin');
});

Route::filter('auth', function()
{	
	$cookie = Cookie::get('refresh_token');
	if(!Session::has('refresh_token')){
		$cookie = Cookie::get('refresh_token');
		Session::put('refresh_token',$cookie);	
	}
});