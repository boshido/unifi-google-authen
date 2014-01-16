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
		$client = new Google_Client();
		$client->setApplicationName("FITM Wifi Authentication Application");
		//$client->setApprovalPrompt("auto");
		$client->setAccessType('offline');
		$oauth2 = new Google_Oauth2Service($client);
		
		$auth_url = $client->createAuthUrl();
		$parameter = array('auth_url' => $auth_url);
		return  Response::view('admin/manage',$parameter);
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
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}

