<?php

class GuestController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function getIndex()
    {
		$unifi = new Unifi();
		$id = Input::get('id');
		$ap = Input::get('ap');
		$ssid = Input::get('ssid');
		$t = Input::get('t');
		$url = Input::get('url');
		
		if($id!=null){
			$chk = md5(uniqid(rand(), true));
			Session::put('id', $id);     //user's mac address
			Session::put('ap', $ap);       //AP mac
			Session::put('ssid',$ssid);   //ssid the user is on (POST 2.3.2)    	
			Session::put('time',$t);		//time the user attempted a request of the portal      	
			Session::put('ref_url',$url);	//url the user attempted to reach   	
			Session::put('auth_code',$chk);				//key to use to check if the user used this form or not
														// -- prevents them from simply going to /authorized.php on their own
			$this->getGoogleAuth();
			return Redirect::to('guest/google-auth')->with('auth_code',$chk);
		}
    }
	
	public function getGoogleAuth(){
	
		$code = Input::get('code');
		$auth_code = Input::get('auth_code');
		
		$client = new Google_Client();
		$client->setApplicationName("FITM Wifi Authentication Application");
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
		$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');

		$oauth2 = new Google_Oauth2Service($client);
		
		
		if ($code != null) { // when finished google authentication
			$client->authenticate($_GET['code']);
			Session::put('token') = $client->getAccessToken();
			if(Session::has('id')){
				$unifi = new Unifi();
				$unifi->sendAuthorization($_SESSION['id'], (12*60)); //authorizing user for 12 hours(12*60)
			}
		}

		if (isset($_SESSION['token'])) {
		 $client->setAccessToken($_SESSION['token']);
		 /*if($client->isAccessTokenExpired()) {
			$client->authenticate();
			$newaccesstoken = json_decode($client->getAccessToken());
			$client->refreshToken($newaccesstoken->refresh_token);
		 }*/
		}

		if ($client->getAccessToken()) {
			return Redirect::to('guest/userinfo');
		} 
		else {
			$auth_url = $client->createAuthUrl();
			return View::make('login', array('auth_url' => $auth_url,'auth_code'=>$auth_code));
		}
		
		
	}
	
	public function postGoogleRedirect(){
		$auth_code =Input::get('auth_code');
		$auth_url = Input::get('auth_url');
		if(Session::has('auth_code') && Session::has('id')){
			if(Session::get('auth_code') == $auth_code){
				$unifi = new Unifi();
				$unifi->sendAuthorization($_SESSION['id'], 1); // authorizing 1 minutes for going through google authentication
				Session::forget('auth_code');
			}
		}
		return Redirect::to($auth_url);
	}
	
	public function getLogout(){
		/*if (isset($_REQUEST['logout'])) {
		  unset($_SESSION['token']);
		  $client->revokeToken();
		}*/
	}	
	
	public function getUserinfo(){
		$unifi = new Unifi();
		//var_dump($unifi->getUser('c8:3d:97:6c:32:08')); //a
		$client = new Google_Client();
		$client->setApplicationName("FITM Wifi Authentication Application");
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
		//$client->setScopes('');
		$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');
		
		$oauth2 = new Google_Oauth2Service($client);

		if (isset($_SESSION['token'])) {
			$client->setAccessToken($_SESSION['token']);
			if($client->isAccessTokenExpired()) {
				$client->authenticate();
				$newaccesstoken = json_decode($client->getAccessToken());
				$client->refreshToken($newaccesstoken->refresh_token);
			}
		}
		
		if ($client->getAccessToken()) {
			$user = $oauth2->userinfo->get();
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			$name = $user['name'];
			$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$img = isset($user['picture']) ? $user['picture'] : '/img/photo.jpg';

			// The access token may have been updated lazily.
			$_SESSION['token'] = $client->getAccessToken();
			return View::make('user',array('name'=>$name,'email'=>$email,'img'=>$img));
		} 
	}
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}