<?php
class GuestController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct()
    {
        $this->beforeFilter('auth');

        //$this->beforeFilter('auth', array('on' => 'post'));

        //$this->afterFilter('log', array('only' =>
                           // array('fooAction', 'barAction')));
    }
	
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
			Session::put('time',$t);		//time the user attempted a request of the portal      	
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
		$db = Database::Connect(); 									// mongodb connect
		$guest = $unifi->getCurrentGuest(Session::get('id'));
		
		// Google Authentication Flow
		if ($code != null) { // when finished google authentication
			try{
				$client->authenticate($_GET['code']); 
			}
			catch(Exception $e){ // code error
				return Redirect::action('GuestController@getSignin');
			}
			
			Session::put('token',$client->getAccessToken());
			while(true){
				try{
					$userinfo = $oauth2->userinfo->get();					// get user infomation
					break;
				}
				catch(Exception $e){ // get user information error

				}	
			}
			
			$google_id = $userinfo['id'];
			$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);
			$fname = $userinfo['given_name'];
			$lname = $userinfo['family_name'];
			
			$accesstoken = json_decode($client->getAccessToken());
			if(isset($accesstoken->refresh_token)){					// store refresh token with userinfo 
				
				$refresh_token = $accesstoken->refresh_token;
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$set = array('$set'=>array('fname'=>$fname,'lname'=>$lname,'email'=>$email,'refresh_token'=>$refresh_token));
				$token->update($find,$set,array("upsert" => true));	
				Session::put('refresh_token',$refresh_token);
				$cookie_refresh = Cookie::forever('refresh_token', $refresh_token);
			}
			else{	// retrieve refresh token from database
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
				$cookie_refresh = Cookie::forever('refresh_token', $result['refresh_token']);
			}
			
			if(Session::has('id')){ // when user has mac address	
					
				$user = $db->user;
				$find = array('mac'=>Session::get('id'));
				$set = array('$set'=>array('email'=>$email));
				$user->update($find,$set);
				$data = $user->findOne($find);
				$unifi->sendAuthorization(Session::get('id'), Session::get('auth_time')); //authorizing user for 6 hours(6*60)
				$cookie_id = Cookie::forever('id',Session::get('id'));
				
				$unifi->setCurrentGuest(Session::get('id'),array('google_id'=>$google_id,'email'=>$email,'hostname'=>$data['hostname']));
			}
			
			return Redirect::to('guest/userinfo')->withCookie($cookie_refresh)->withCookie($cookie_id);
		}

		// Normal Flow	
		if($guest && isset($guest->google_id)){
			if(!Session::has('refresh_token')){
				$token = $db->token;
				$user = $db->user;
				
				$find = array('google_id'=>$guest->google_id);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
				$cookie_refresh = Cookie::forever('refresh_token', $result['refresh_token']);
				
				$find = array('mac'=>$guest->mac);
				$data = $user->findOne($find);
				$cookie_id = Cookie::forever('id',Session::get('id'));
				return Redirect::to('guest/userinfo')->withCookie($cookie_refresh)->withCookie($cookie_id);
			}
			else{
				return Redirect::to('guest/userinfo');
			} 
		} 
		else {	
			$auth_url = $client->createAuthUrl();
			$parameter = array('auth_url' => $auth_url);
			
			if(!Session::has('auth_code')){
				$chk = md5(uniqid(rand(), true));
				Session::put('auth_code',$chk);		// key for checking user that used this form or not
			}
			$parameter['auth_code'] = Session::get('auth_code');
			
			if(!Session::has('id')){ // when user doesn't has mac address
				$info = $unifi->getUser(array('ip'=> $_SERVER['REMOTE_ADDR']));
				if($info){
					Session::put('id', $info->mac);     				// user's mac address
					Session::put('ap', $info->ap_mac);   				// AP mac
					Session::put('ssid',$info->essid);   				// ssid the user is on (POST 2.3.2)    	
					//Session::put('time',null);
					Session::put('ref_url','http://www.google.co.th');	// url the user attempted to reach
				}
				else{
					$parameter['init'] = true;
				}
			}
			
			return  Response::view('login', $parameter);
		}		
		
	}
	
	public function getGoogleRedirect(){
		$auth_code =Input::get('auth_code');
		$auth_url = Input::get('auth_url');
		$remember = Input::get('remember');
		if($remember != null)Session::put('auth_time',99999999);
		else Session::put('auth_time',360);
		
		if(Session::has('auth_code') && Session::has('id')){
			if(Session::get('auth_code') == $auth_code){
				$unifi = new Unifi();
				$unifi->sendAuthorization(Session::get('id'), 5); // authorizing 1 minutes for going through google authentication
				Session::forget('auth_code');
				return Response::view('loading', array('url' => $auth_url,'flag'=>'signin'));
			}
		}
		else{
			return Redirect::action('GuestController@getSignin');
		}
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
		
		$cookie_refresh = Cookie::forget('refresh_token');
		Session::flush();
		
		return Response::view('loading', array('url' => action('GuestController@getSignin'),'flag'=>'signout'))->withCookie($cookie_refresh);
	}	
	
	public function getUserinfo(){
		$unifi = new Unifi();
		$guest = $unifi->getCurrentGuest(Session::get('id'));
		if($guest && isset($guest->google_id) && Session::has('refresh_token')){
			
			$client = new Google_Client();
			$client->setApprovalPrompt("auto");
			$client->setAccessType('offline');
			$oauth2 = new Google_Oauth2Service($client);
			
			
			if (Session::has('token')) {
				$client->setAccessToken(Session::get('token'));
			}
			if($client->isAccessTokenExpired()) {
				$client->refreshToken(Session::get('refresh_token'));
			}
			
			if ($client->getAccessToken()) {
				while(true){
					try{
						$userinfo = $oauth2->userinfo->get();	// get user infomation
						break;
					}
					catch(Exception $e){
						if ($e instanceof Google_ServiceException) {
						   return Redirect::action('GuestController@getSignout');
						}
						if ($e instanceof Google_IOException) {
						}
					}
				}
				
				// These fields are currently filtered through the PHP sanitize filters.
				// See http://www.php.net/manual/en/filter.filters.sanitize.php
				$google_id = $userinfo['id'];
				$name = $userinfo['given_name'];
				$surname = $userinfo['family_name'];
				$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);
				$img = isset($userinfo['picture']) ? $userinfo['picture'] : '/img/photo.jpg';
				$login_at = date("d/m/y H:i:s",$guest->start);
				$login_at = substr_replace($login_at,(int)date("y",$guest->start)+43,6,2);
				// The access token may have been updated lazily.
				Session::put('token',$client->getAccessToken());
				return Response::view('user',array('google_id'=>$google_id,'name'=>$name,'surname'=>$surname,'email'=>$email,'img'=>$img,'end_time'=>$guest->end ,'login_at'=>$login_at));
			
			}

		}
		else{
			return Redirect::action('GuestController@getSignin');
		}
	}
	
	public function getInitinfo(){
		$unifi = new Unifi();
		$info = $unifi->getUser(array('ip'=> $_SERVER['REMOTE_ADDR']));
		if($info){
			Session::put('id', $info->mac);     				// user's mac address
			Session::put('ap', $info->ap_mac);   				// AP mac
			Session::put('ssid',$info->essid);   				// ssid the user is on (POST 2.3.2)    	
			Session::put('ref_url','http://www.google.co.th');	// url the user attempted to reach
			$response['status'] = true;
		}
		else{
			$response['status'] = false;
		}
		return Response::json($response);
	}
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}

