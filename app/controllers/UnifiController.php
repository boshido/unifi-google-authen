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
		
		return Response::json(array('code'=>200,'data'=>$result));
	}
	
	public function postDeactiveSession()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnAuthorization($mac);
		return 1;
	}
	
	public function getUserHistory()
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
		
		return Response::json(array('code'=>200,'data'=>$result));
	}
	
	public function getUser()
	{
		$ip = Input::get('ip');
		$mac = Input::get('mac');
		
		$find = array();
		if($ip != null)$find['ip']=$ip;
		if($mac != null)$find['mac']=$mac;
		
		$unifi = new Unifi();
		$mac = $unifi->getUser($find);
		if($mac != false){
			$user = $unifi->getCurrentGuest($mac->mac);
			if($user != false)return Response::json(array('code'=>200,'data'=>$user));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}
	
	public function getUserList(){
		$unifi = new Unifi();
		$user  = $unifi->getUser(array('all'=>true));
		$guest = array();
		$authorized = 0;
		$non_authorized = 0;
		if($user){
			foreach($user as $key => $value){
				if($value->is_guest == 1){
					if($unifi->getCurrentGuest($value->mac))$authorized++;
					else $non_authorized++;
				}
			}	
		}
		return Response::json(array('code'=>200,'authorized'=>$authorized,'non_authorized'=>$non_authorized));
	}
	
	public function getDevice()
	{
		$mac = Input::get('mac');
		
		$find = array();
		if($mac != null)$find['mac']=$mac;
		$db = Database::Connect();
		$user = $db->user;
		
		$unifi = new Unifi();
		$device = $unifi->getUser($find);

		if($device != false){
			$result = array('code'=>200,'data'=>$device);
		}
		else{
			$device = $user->findOne($find);
			$result = array('code'=>206,'data'=>$device);
		}
		
		if($device != null)return Response::json($result);
		else return Response::json(array('code'=>404));
		
	}
	
	public function getAp()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		
		return Response::json(array('code'=>200,'data'=>$unifi->getAp($mac)));
	}
	
	public function getStat()
	{
		$mac = Input::get('mac');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		
		$sort_type =  Input::get('sort_type') != null ? (int)Input::get('sort_type') : 1;
		$unifi = new Unifi();
		//'mac'=>$mac,'limit'=>$limit,'sort'=>$sort,'sort_type'=>$sort_type
		return Response::json(array('code'=>200,'data'=>$unifi->getStat($mac,$limit,$sort,$sort_type)));
	}
	
	public function getStatDaily()
	{	
		$mac = Input::get('mac');
		$at = Input::get('at') ;
		$to = Input::get('to') ; 
		$unifi = new Unifi();
		
		return Response::json(array('code'=>200,'data'=>$unifi->getStatDaily($mac,$at,$to)));
	}
	
	public function getStatSummary()
	{	
		$type = Input::get('type');
		$data = Input::get('data');
		$unifi = new Unifi();
		
		return Response::json(array('code'=>200,'data'=>$unifi->getStatSummary($type,$data)));
	}
	
	public function getUserTable(){
	
		$unifi = new Unifi();
		$db = Database::Connect();
		$token = $db->token;
		$cursor = $token->find();
		$user = array();
		$result = array();
		$guest_tmp = $unifi->getCurrentGuest(null,false);
		$online = $unifi->getUser(array('all'=>true));
		
		foreach($guest_tmp as $key =>$value){
			$guest[$value['mac']]=$value;
		}
		
		if($online){ // Online check with       Online User  and  Guest table
			foreach($online as $key => $value){
				if($value->is_guest == 1){
					if(isset($guest[$value->mac])){
						$guest[$value->mac]['online']=true;
					}
				}
			}	
		}
		
		foreach($cursor as $key => $value){
			if(!isset($value['name']) || $value['name'] == '-'){
				if($value['fname'] != '-' && $value['lname'] != '-'){
					$value['name'] = $value['fname'].' '.$value['lname'];
				}
			}
			$user[$value['google_id']] = $value;			
		}
		foreach($guest as $key =>$value){
			if(isset($value['google_id'])){
				$user[$value['google_id']]['authorized'] = true;
				$tmp = array('auth_type'=>$value['auth_type'],'mac'=>$value['mac'],'start'=>$value['start'],'end'=>$value['end']);	
				if(isset($value['hostname'])) $tmp['hostname'] = $value['hostname'];
				
				if(isset($value['online'])){
					$user[$value['google_id']]['status']=1;
					$user[$value['google_id']]['online'][] = $tmp;
				}
				else{
					$user[$value['google_id']]['offline'][] = $tmp;
				}
			}
		}
		foreach($user as $key =>$value){
			$result[]=$value;
		}
		
		return Response::json(array('code'=>200,'aaData'=>$result));
		
	}
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}