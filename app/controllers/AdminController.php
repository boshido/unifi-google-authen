<?php
class AdminController extends Controller {

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
		return  Response::view('admin/signin');
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
				$name = $userinfo['name'];
				$fname = isset($userinfo['given_name']) ? $userinfo['given_name'] : '-';
				$lname = isset($userinfo['family_name']) ? $userinfo['family_name'] : '-';
				$email = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);
				$img = isset($userinfo['picture']) ? $userinfo['picture'] : '/img/photo.jpg';
				$login_at = date("d/m/y H:i:s",$guest->start);
				$login_at = substr_replace($login_at,(int)date("y",$guest->start)+43,6,2);
				// The access token may have been updated lazily.
				Session::put('token',$client->getAccessToken());
				return Response::view('user',array('google_id'=>$google_id,'name'=>$name,'fname'=>$fname,'lname'=>$lname,'email'=>$email,'img'=>$img,'end_time'=>$guest->end,'auth_type'=>$guest->auth_type,'device'=>$guest->hostname ,'login_at'=>$login_at));
				
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

