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
			Session::put('id', $id);     //user's mac address
			Session::put('ap', $ap);       //AP mac
			Session::put('ssid',$ssid);   //ssid the user is on (POST 2.3.2)    	
			Session::put('time',$t);		//time the user attempted a request of the portal      	
			Session::put('ref_url',$url);	//url the user attempted to reach   	
														// -- prevents them from simply going to /authorized.php on their own
			
			return Redirect::action('GuestController@getGoogleAuth');
		}
    }
	
	public function getGoogleAuth(){
	
		$code = Input::get('code');
		
		$client = new Google_Client();
		$client->setApplicationName("FITM Wifi Authentication Application");
		$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');

		$oauth2 = new Google_Oauth2Service($client);

		if ($code != null) { // when finished google authentication
			$client->authenticate($_GET['code']);
			Session::put('token',$client->getAccessToken());
			var_dump($client->getAccessToken());
			if(Session::has('id')){
				$newaccesstoken = json_decode($client->getAccessToken());
				if(isset($newaccesstoken->refresh_token)){
					$refresh_token = $newaccesstoken->refresh_token;
					
				}
				$user = $oauth2->userinfo->get();
				$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
				$db = Database::Connect();
				$user = $db->user;
				$find = array('mac'=>'c8:3d:97:6c:32:08');
				$set =  array('$set'=>array('fitm'=>$email));
				$user->update($find,$set);
				
				$unifi = new Unifi();
				$unifi->sendAuthorization(Session::get('id'), (12*60)); //authorizing user for 12 hours(12*60)
			}
		}
		if (Session::has('token')) {
			$client->setAccessToken(Session::get('token'));
		}
		if ($client->getAccessToken()) {
			//return Redirect::to('guest/userinfo');
		} 
		else {
			$chk = md5(uniqid(rand(), true));
			Session::put('auth_code',$chk);				//key to use to check if the user used this form or not
			$auth_url = $client->createAuthUrl();
			return View::make('login', array('auth_url' => $auth_url,'auth_code'=>$chk));
		}		
		
	}
	
	public function getGoogleRedirect(){
		$auth_code =Input::get('auth_code');
		$auth_url = Input::get('auth_url');
		if(Session::has('auth_code') && Session::has('id')){
			if(Session::get('auth_code') == $auth_code){
				$unifi = new Unifi();
				$unifi->sendAuthorization(Session::get('id'), 1); // authorizing 1 minutes for going through google authentication
				Session::forget('auth_code');
			}
		}
		return View::make('loading', array('auth_url' => $auth_url));
	}
	
	public function getSignout(){
	
		Session::flush();
		return Redirect::action('GuestController@getGoogleAuth');
	}	
	
	public function getUserinfo(){
		$unifi = new Unifi();
		//var_dump($unifi->getUser('c8:3d:97:6c:32:08')); //a
		$client = new Google_Client();
		$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');
		
		$oauth2 = new Google_Oauth2Service($client);
		
		if (Session::has('token')) {
			$client->setAccessToken(Session::get('token'));
			var_dump(Session::get('token'));
			if($client->isAccessTokenExpired()) {
				$client->authenticate();
				$newaccesstoken = json_decode($client->getAccessToken());

				//$client->refreshToken($newaccesstoken->refresh_token);
			}
		}
		
		if ($client->getAccessToken()) {
			//$user = $oauth2->userinfo->get();
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			/*$name = $user['name'];
			$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$img = isset($user['picture']) ? $user['picture'] : '/img/photo.jpg';

			// The access token may have been updated lazily.
			Session::put('token',$client->getAccessToken());
			return View::make('user',array('name'=>$name,'email'=>$email,'img'=>$img));*/
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