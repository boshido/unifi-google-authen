<?php

class UnifiController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function getIndex()
    {
		
		
    }
	
	public function getActiveSession()
	{
		$google_id = Input::get('google_id');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		$sort_type = Input::get('sort_type') != null ? (int)Input::get('sort_type') : 1;

		$db = Database::Connect();
		$guest = $db->guest;
		$user = $db->user;
		$result = array();
		$time = time();
		
		$find = array('google_id' => $google_id,'$and'=>array(array('start'=>array('$lte'=>$time)),array('end'=>array('$gte'=>$time))));
		if($limit != null) $cursor = $guest->find($find)->limit($limit);
		else $cursor = $guest->find($find);
		
		if($sort != null){
			$cursor->sort(array($sort=>$sort_type));
		}
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$tmp = $user->findOne(array('mac'=>$value['mac']));
			$value['last_seen'] = $tmp['last_seen'];
			$result[] =$value;
		}
		
		return Response::json($result);
	}
	
	public function postDeactiveSession()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnAuthorization($mac);
		return 1;
	}
	
	public function getHistory()
	{
		$google_id = Input::get('google_id');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		$sort_type = Input::get('sort_type') != null ? (int)Input::get('sort_type') : 1;

		$db = Database::Connect();
		$guest = $db->guest;
		$result = array();
		
		$find = array('google_id' => $google_id);
		if($limit != null) $cursor = $guest->find($find)->limit($limit);
		else $cursor = $guest->find($find);
		
		if($sort != null){
			$cursor->sort(array($sort=>$sort_type));
		}
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$result[] =$value;
		}
		
		return Response::json($result);
	}
	
	public function getUser()
	{
		$ip = Input::get('ip');
		$unifi = new Unifi();
		$mac = $unifi->getUser(array('ip'=>$ip));
		if($mac != false){
			$user = $unifi->getCurrentGuest($mac->mac);
			if($user != false)return Response::json(array('code'=>200,'data'=>$user));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}
	
	public function getStat()
	{
		$mac = Input::get('mac');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		$sort_type =  Input::get('sort_type') != null ? Input::get('sort_type') : 1;
		$unifi = new Unifi();
		
		return Response::json($unifi->getStat($mac,$limit,$sort,$sort_type));
	}
	
	public function getStatDaily()
	{	
		$mac = Input::get('mac');
		$at = Input::get('at') ;
		$to = Input::get('to') ; 
		$unifi = new Unifi();
		
		return Response::json($unifi->getStatDaily($mac,$at,$to));
	}
	
	public function getStatSummary()
	{	
		$type = Input::get('type');
		$data = Input::get('data');
		$unifi = new Unifi();
		
		return Response::json($unifi->getStatSummary($type,$data));
	}
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}