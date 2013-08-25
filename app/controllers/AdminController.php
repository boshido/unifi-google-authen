<?php
class AdminController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct()
    {
        $this->beforeFilter('management',array('only' =>array('getIndex')));

        //$this->beforeFilter('auth', array('on' => 'post'));

        //$this->afterFilter('log', array('only' =>
                           // array('fooAction', 'barAction')));
    }
	
	public function getIndex()
    {
		return  Response::view('admin/manage');
    }
	
	public function getSignin(){
		if(!Session::has('login')){
			return  Response::view('admin/signin');
		}
		else{
			return Redirect::action('AdminController@getIndex');
		}
	}
	
	public function postSignin(){
		$username = Input::get('username');
		$password = Input::get('password');
		$db = Database::Connect();
		$admin = $db->admin;
		$user = $admin->findOne(array('name'=>$username,'x_password'=>$password));
		if($user != null){
			Session::put('login',$username);
			return Redirect::action('AdminController@getIndex');
		}
		else{
			return Redirect::action('AdminController@getSignin');
		}
	}
	
	public function getSignout(){
	
		Session::flush();
		return Redirect::action('AdminController@getSignin');
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

