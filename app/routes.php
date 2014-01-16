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
	$unifi = new Unifi();
	$db = Database::Connect(); 	
	
	if(!Session::has('id')){ // when user doesn't has mac address	
		if(Cookie::has('id')){
			$cookie = Cookie::get('id');
			Session::put('id',$cookie);
		}
		else{
			$info = $unifi->getDevice(array('ip'=> $_SERVER['REMOTE_ADDR']));
			if($info){
				Session::put('id', $info->mac);     				// user's mac address
				Session::put('ap', $info->ap_mac);   				// AP mac
				Session::put('ssid',$info->essid);   				// ssid the user is on (POST 2.3.2)    	
				//Session::put('time',null);
				Session::put('ref_url','http://www.google.co.th');	// url the user attempted to reach
			}
		}
	}
	if(!Session::has('refresh_token')){
		if(Cookie::has('refresh_token')){
			$cookie = Cookie::get('refresh_token');
			Session::put('refresh_token',$cookie);	
		}
		else{
			$guest = Session::has('id') ? $unifi->getCurrentGuest(Session::get('id')) : false;
			if($guest && isset($guest['google_id'])){
				$token = $db->token;
				$find = array('google_id'=>$guest['google_id']);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
				$cookie_refresh = Cookie::forever('refresh_token', $result['refresh_token']);
			}
		}
	}
});

Route::filter('management', function()
{	
	if(!Session::has('login')){
		return 	Redirect::action('AdminController@getSignin');
	}
});