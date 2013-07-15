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
			Session::put('id', $id);     	//user's mac address
			Session::put('ap', $ap);       	//AP mac
			Session::put('ssid',$ssid);   	//ssid the user is on (POST 2.3.2)    	
			//Session::put('time',$t);		//time the user attempted a request of the portal      	
			Session::put('ref_url',$url);	//url the user attempted to reach   	
														// -- prevents them from simply going to /authorized.php on their own
			
			return Redirect::action('GuestController@getSignin');
		}
    }
	
	public function getSignin(){
	
		$code = Input::get('code');
		$client = new Google_Client();
		$client->setApplicationName("FITM Wifi Authentication Application");
		$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');
		$oauth2 = new Google_Oauth2Service($client);
		$unifi = new Unifi();
		if(!Session::has('id')){// when no id redirect user to outside network
			//$info1 = $unifi->getUser(array('all'=>true));
			//var_dump($info1);
			$info = $unifi->getUser(array('ip'=> $_SERVER['REMOTE_ADDR']));
			if($info){
				Session::put('id', $info->mac);     				// user's mac address
				Session::put('ap', $info->ap_mac);   				// AP mac
				Session::put('ssid',$info->essid);   				// ssid the user is on (POST 2.3.2)    	
				//Session::put('time',null);
				Session::put('ref_url','http://www.google.co.th');	// url the user attempted to reach
			}
		}
		
		if ($code != null) { // when finished google authentication
			$client->authenticate($_GET['code']);
			Session::put('token',$client->getAccessToken());
			$userinfo = $oauth2->userinfo->get();					// get user infomation
			$db = Database::Connect();								// mongodb connect
			
			$accesstoken = json_decode($client->getAccessToken());
			if(isset($accesstoken->refresh_token)){					// store refresh token with userinfo
				$google_id = $userinfo['id'];
				$fname = $userinfo['given_name'];
				$lname = $userinfo['family_name'];
				$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);
				
				$refresh_token = $accesstoken->refresh_token;
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$set = array('$set'=>array('fname'=>$fname,'lname'=>$lname,'email'=>$email,'refresh_token'=>$refresh_token));
				$token->update($find,$set,array("upsert" => true));
					
				Session::put('refresh_token',$refresh_token);
			}
			else{	// Retrieve refresh token from database
				$google_id = $userinfo['id'];
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
			}
			
			if(Session::has('id')){ // when user has mac address
				
				$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);	
				$user = $db->user;
				$find = array('mac'=>Session::get('id'));
				$set =  array('$set'=>array('email'=>$email));
				$user->update($find,$set);
				
				$unifi->sendAuthorization(Session::get('id'), (12*60)); //authorizing user for 12 hours(12*60)
			}
		}

		if ($client->getAccessToken()) {
			return Redirect::to('guest/userinfo');
		} 
		else {
			if(!Session::has('auth_code')){
				$chk = md5(uniqid(rand(), true));
				Session::put('auth_code',$chk);				//key to use to check if the user used this form or not
			}
			$auth_url = $client->createAuthUrl();
			return View::make('login', array('auth_url' => $auth_url,'auth_code'=>Session::get('auth_code')));
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
		return View::make('loading', array('url' => $auth_url,'second'=>2));
	}
	
	public function getSignout(){
		/*if(Session::has('token')){
			$client = new Google_Client();
			$client->setApprovalPrompt("auto");
			$client->setAccessType('offline');
			$client->setAccessToken(Session::get('token'));
			$client->revokeToken();
				
		}*/
		$unifi = new Unifi();
		$unifi->sendUnAuthorization(Session::get('id'));
		
		Session::flush();
		return View::make('loading', array('url' => action('GuestController@getSignin'),'second'=>7));
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
			if($client->isAccessTokenExpired()) {
				$client->refreshToken(Session::get('refresh_token'));
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
			Session::put('token',$client->getAccessToken());
			return View::make('user',array('name'=>$name,'email'=>$email,'img'=>$img));
		}
		else{
			return Redirect::action('GuestController@getSignin');
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