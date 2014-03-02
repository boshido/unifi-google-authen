<?php
class GuestController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct()
    {
        $this->beforeFilter('auth',array('only'=>array('getSignin','getUserinfo')));

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
		//$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');
		$oauth2 = new Google_Oauth2Service($client);
		$unifi = new Unifi();
		$db = Database::Connect(); 									// mongodb connect
		$guest = Session::has('id') ? $unifi->getCurrentGuest(Session::get('id')) : false;
		
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
			$name =  isset($userinfo['name']) ? $userinfo['name'] : '-';
			$fname = isset($userinfo['given_name']) ? $userinfo['given_name'] : '-';
			$lname = isset($userinfo['family_name']) ? $userinfo['family_name'] : '-';
			$picture = isset($userinfo['picture']) ? str_replace("?sz=50", "", $userinfo['picture']) : null;

			
			$accesstoken = json_decode($client->getAccessToken());
			if(isset($accesstoken->refresh_token)){					// store refresh token with userinfo 
				
				$refresh_token = $accesstoken->refresh_token;
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$set = array('$set'=>array('name'=>$name,'fname'=>$fname,'lname'=>$lname,'email'=>$email,'picture'=>$picture,'refresh_token'=>$refresh_token));
				$token->update($find,$set,array("upsert" => true));	
				Session::put('refresh_token',$refresh_token);
				//$cookie_refresh = Cookie::forever('refresh_token', $refresh_token);
			}
			else{	// retrieve refresh token from database
				$token = $db->token;
				$find = array('google_id'=>$google_id);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
				//$cookie_refresh = Cookie::forever('refresh_token', $result['refresh_token']);
			}
			
			if(Session::has('id')){ // when user has mac address
				$quest_code = md5(uniqid(rand(), true));	
				Session::put('quest_code',$quest_code);
				return Redirect::to('questionnaire/value.php?qest_code='.$quest_code.'&mac='.Session::get('id').'&ap='.Session::get('ap').'&name='.$name.'&fname='.$fname.'&lname='.$lname.'&google_id='.$google_id.'&email='.$email.'&auth_type='.Session::get('auth_type'));

				$user = $db->user;
				$find = array('mac'=>Session::get('id'));
				$set = array('$set'=>array('email'=>$email));
				$user->update($find,$set);
				$data = $user->findOne($find);
				$unifi->sendAuthorization(Session::get('id'), Session::get('auth_type') == 0 ? 180 : 9999999, Session::get('ap')); //authorizing user for 6 hours(6*60)
				//$cookie_id = Cookie::forever('id',Session::get('id'));
				$guestinfo = array('name'=>$name,'fname'=>$fname,'lname'=>$lname,'google_id'=>$google_id,'email'=>$email,'auth_type'=>Session::get('auth_type'));
				if(isset($data['hostname']))$guestinfo['hostname']=$data['hostname'];
				$unifi->setCurrentGuest(Session::get('id'),$guestinfo);
			}

			return Redirect::to('guest/userinfo');//->withCookie($cookie_refresh)->withCookie($cookie_id);
		}

		// Normal Flow	
		if($guest && isset($guest['google_id'])){
			/*if(!Session::has('refresh_token')){
				$token = $db->token;
				$find = array('google_id'=>$guest['google_id']);
				$result = $token->findOne($find);
				Session::put('refresh_token',$result['refresh_token']);
				$cookie_refresh = Cookie::forever('refresh_token', $result['refresh_token']);
				//$user = $db->user;
				//$find = array('mac'=>$guest['mac']);
				//$data = $user->findOne($find);
				//$cookie_id = Cookie::forever('id',Session::get('id'));
				//return Redirect::to('guest/userinfo')->withCookie($cookie_refresh)->withCookie($cookie_id);
				return Redirect::to('guest/userinfo')->withCookie($cookie_refresh);
			}
			else{
				return Redirect::to('guest/userinfo');
			} 
			*/
			return Redirect::to('guest/userinfo');
		} 
		else {	
			// When user is not authorized
			$auth_url = $client->createAuthUrl();
			$parameter = array('auth_url' => $auth_url);
			
			if(!Session::has('auth_code')){
				$chk = md5(uniqid(rand(), true));
				Session::put('auth_code',$chk);		// key for checking user that used this form or not
			}
			$parameter['auth_code'] = Session::get('auth_code');
			if(!Session::has('id'))$parameter['init'] = true;
			
			return  Response::view('auth/signin', $parameter);
		}		
		
	}
	
	public function getGoogleRedirect(){
		$auth_code =Input::get('auth_code');
		$auth_url = Input::get('auth_url');
		$remember = Input::get('remember');
		if($remember != null)Session::put('auth_type',1);
		else Session::put('auth_type',0);
		
		if(Session::has('auth_code') && Session::has('id')){
			if(Session::get('auth_code') == $auth_code){
				$unifi = new Unifi();
				$unifi->sendAuthorization(Session::get('id'), 5 , Session::get('ap')); // authorizing 1 minutes for going through google authentication
				Session::forget('auth_code');
				return Response::view('auth/loading', array('url' => $auth_url,'flag'=>'signin'));
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
		
		//$cookie_refresh = Cookie::forget('refresh_token');
		Session::flush();
		
		return Response::view('auth/loading', array('url' => action('GuestController@getSignin'),'flag'=>'signout'));//->withCookie($cookie_refresh);
	}	
	
	public function getClearSession(){
		Session::flush();
	}	
	
	public function getUserinfo(){
		$unifi = new Unifi();
		$guest = Session::has('id') ? $unifi->getCurrentGuest(Session::get('id')) : false;
		if($guest && isset($guest['google_id']) && Session::has('refresh_token')){
			
			$client = new Google_Client();
			//$client->setApprovalPrompt("auto");
			$client->setAccessType('offline');
			$oauth2 = new Google_Oauth2Service($client);
			
			
			if (Session::has('token')) {
				$client->setAccessToken(Session::get('token'));
			}
			if($client->isAccessTokenExpired()) {
				try{
					$client->refreshToken(Session::get('refresh_token'));
				}
				catch(Exception $e){
					return Redirect::action('GuestController@getSignout');
				}
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
				$name = isset($userinfo['name']) ? $userinfo['name'] : '-';
				$fname = isset($userinfo['given_name']) ? $userinfo['given_name'] : '-';
				$lname = isset($userinfo['family_name']) ? $userinfo['family_name'] : '-';
				$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);
				$img = isset($userinfo['picture']) ? str_replace("?sz=50", "", $userinfo['picture']) : '/img/photo.jpg';
				$device = isset($guest['hostname']) ? $guest['hostname'] : '-';
				$signin_at = date("d/m/y H:i:s",$guest['start']);
				$signin_at = substr_replace($signin_at,(int)date("y",$guest['start'])+43,6,2);
				
				// The access token may have been updated lazily.
				Session::put('token',$client->getAccessToken());
				return Response::view('auth/user',array('google_id'=>$google_id,'name'=>$name,'fname'=>$fname,'lname'=>$lname,'email'=>$email,'img'=>$img,'end_time'=>$guest['end'],'auth_type'=>$guest['auth_type'],'device'=> $device,'signin_at'=>$signin_at));
				
			}

		}
		else{
			return Redirect::action('GuestController@getSignin');
		}
	}
	
	public function getInitinfo(){
		$unifi = new Unifi();
		$info = $unifi->getDevice(array('ip'=> $_SERVER['REMOTE_ADDR']));
		if($info){
			Session::put('id', $info['mac']);     				// user's mac address
			Session::put('ap', $info['ap_mac']);   				// AP mac
			Session::put('ssid',$info['essid']);   				// ssid the user is on (POST 2.3.2)    	
			Session::put('ref_url','http://www.google.co.th');	// url the user attempted to reach
			$response['status'] = true;
		}
		else{
			$response['status'] = false;
		}
		return Response::json($response);
	}

	public function getAuthorize(){
		$qest_code 	= Input::get('quest_code');
		$mac 		= Input::get('mac');
		$ap 		= Input::get('ap');
		$google_id 	= Input::get('google_id');
		// $name 		= Input::get('name');
		// $fname 		= Input::get('fname');
		// $lname 		= Input::get('lname');
		// $email 		= Input::get('email');

		$auth_type 	= Input::get('auth_type');

		if(Session::get('qest_code') == $qest_code && isset($mac) && $mac != "" && isset($google_id)){
			$unifi = new Unifi();
			$db = Database::Connect(); 
			$user = $db->user;
			$token = $db->token;
			$googleAccount = $token->findOne(array('google_id'=>$google_id));

			if($googleAccount){
				$name = $googleAccount['name'];
				$fname = $googleAccount['fname'];
				$lname = $googleAccount['lname'];
				$email = $googleAccount['email'];

				$find = array('mac'=>$mac);
				$set = array('$set'=>array('email'=>$email));
				$user->update($find,$set);

				$unifi->sendAuthorization($mac,$auth_type == 0 ? 180 : 9999999, $ap); //authorizing user for 6 hours(6*60)
				//$cookie_id = Cookie::forever('id',Session::get('id'));
				$data = $user->findOne($find);
				$guestinfo = array('name'=>$name,'fname'=>$fname,'lname'=>$lname,'google_id'=>$google_id,'email'=>$email,'auth_type'=>$auth_type);
				if(isset($data['hostname']))$guestinfo['hostname']=$data['hostname'];
				$unifi->setCurrentGuest($mac,$guestinfo);
				return Redirect::to('guest/userinfo');
			}
			else{
				return Redirect::action('GuestController@getSignin');
			}
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

