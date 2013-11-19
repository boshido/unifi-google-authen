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
	
	public function postAuthorize()
	{
		$mac = Input::get('device-mac');
		$hostname = Input::get('device-name');
		$google_id = Input::get('google-id');
		$fname = Input::get('google-fname');
		$lname = Input::get('google-lname');
		$email = Input::get('google-email');
		
		$unifi = new Unifi();
		$db = Database::Connect();
		$user = $db->user;
		$find = array('mac'=>$mac);
		$set = array('$set'=>array('email'=>$email));
		$user->update($find,$set);
		
		$unifi->sendAuthorization($mac,9999999); 
		$guestinfo = array('google_id'=>$google_id,'email'=>$email,'auth_type'=>1);
		if($hostname != '')$guestinfo['hostname']=$hostname;
		
		$unifi->setCurrentGuest($mac,$guestinfo);
		
		return 1;
	}
	
	public function postDeactiveSession()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnAuthorization($mac);
		return 1;
	}
	public function postReconnect()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendReconnect($mac);
		return 1;
	}
	public function postRestartAp()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendRestartAp($mac);
		return 1;
	}
	public function postBlock()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendBlock($mac);
		return 1;
	}
	public function postUnBlock()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnBlock($mac);
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
	
	public function getDeviceCount(){
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
		return Response::json(array('code'=>200,'data'=>array('authorized'=>$authorized,'non_authorized'=>$non_authorized)));
	}
	
	public function getDevice()
	{
		$mac = Input::get('mac');
		
		$find['mac']=$mac;
		$db = Database::Connect();
		$user = $db->user;
		
		$unifi = new Unifi();
		$device = $unifi->getUser($find);
		$is_auth =  $unifi->getCurrentGuest($mac);
		$is_auth = $is_auth ? true : false; 
		
		if($device != false && $device->is_guest){
			$result = array('code'=>200,'data'=>$device,'is_auth'=>$is_auth);
		}
		else{
			$device = $user->findOne($find);
			$result = array('code'=>206,'data'=>$device,'is_auth'=>$is_auth);
		}
		
		if($device != null)return Response::json($result);
		else return Response::json(array('code'=>404));
		
	}
	
	public function getApCount()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$ap = $unifi->getAp($mac);
		$connected=0;
		$disconnected=0;
		foreach($ap as $key => $value){
			if($value->state==1) $connected++;
			else $disconnected++;
		}
		
		return Response::json(array('code'=>200,'data'=>array('connected'=>$connected,'disconnected'=>$disconnected)));
	}
	public function getAp()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$ap = $unifi->getAp($mac);
		unset($ap['vap_table']);
		return Response::json(array('code'=>200,'data'=>$ap));
	}

	public function getApDevice()
	{
		$mac = Input::get('mac');

		$db = Database::Connect();
		$user = $db->user;

		$unifi = new Unifi();
		$ap = $unifi->getAp($mac);
		if( $mac != null &&  $ap != false){
			$find = array();
			$tmp = array();
			$result = array();
			foreach($ap['vap_table'] as $key => $ssid){
				if($ssid['is_guest']){
					$device = $ssid['sta_table'];
					for($i=0;$i<count($device);$i++){
						array_push($find,$device[$i]['mac']);
						$tmp[$device[$i]['mac']]=$device[$i];
					}
				}
			}
			if(count($find)>0){
				$is_auth =  $unifi->getCurrentGuest($find,false);
				if($is_auth != false){
					foreach($is_auth as $key => $device){
						$tmp[$device['mac']]['google_id'] = $device['google_id'];
						$tmp[$device['mac']]['email'] = $device['email'];
						$tmp[$device['mac']]['hostname'] = $device['email'];
						$tmp[$device['mac']]['auth_type'] = $device['auth_type'];
					
						array_push($result,$tmp[$device['mac']]);
						unset($tmp[$device['mac']]);
					}

					return Response::json(array('code'=>200,'data'=>$result));
				}
				else return Response::json(array('code'=>204 ,'data'=>array()));
			}
			else return Response::json(array('code'=>204 ,'data'=>array()));
		}
		else return Response::json(array('code'=>404));
		
	}
	
	public function getMapList()
	{
		$id = Input::get('id');
		$unifi = new Unifi();
		$maplist = $unifi->getMapList($id);
		
		return Response::json(array('code'=>200,'data'=>$maplist));
	}
	
	public function getMap()
	{
		$id = Input::get('id');
		$unifi = new Unifi();
		$map = $unifi->getMap($id);
		$type = 'image/png';
		if($map)return Response::make($map, 200)->header('Content-Type',$type);
		else return Response::make('', 400);
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
	
	public function getAuthorizedDevice(){
		
		$google_id = Input::get('google_id');
		$unifi = new Unifi();
		$db = Database::Connect();
		$guest = $db->guest;
		$result = array();
		$tmp = array();
		$time = time();
		$find = array('google_id'=>$google_id,'$and'=>array(array('start'=>array('$lte'=>$time)),array('end'=>array('$gte'=>$time))));
		$cursor = $guest->find($find);
		$online = $unifi->getUser(array('all'=>true));
		if($online){
			foreach($online as $key => $value){
				$tmp[$value->mac]=$value;
			}
		}
		
		foreach($cursor as $key => $value){
			$value['_id']=(string)$value['_id'];
			if(isset($tmp[$value['mac']]) && $tmp[$value['mac']]->is_guest == 1){
				$result['online'][]=$value;
			}
			else{
				$result['offline'][]=$value;
			}
		}
		
		if(count($result)>0)return Response::json(array('code'=>200,'data'=>$result));
		else return Response::json(array('code'=>404));
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
			$value['online']=0;
			$value['offline']=0;
			$user[$value['google_id']] = $value;			
		}
		foreach($guest as $key =>$value){
			if(isset($value['google_id'])){
				$user[$value['google_id']]['authorized'] = true;
				$tmp = array('auth_type'=>$value['auth_type'],'mac'=>$value['mac'],'start'=>$value['start'],'end'=>$value['end']);	
				if(isset($value['hostname'])) $tmp['hostname'] = $value['hostname'];
				
				if(isset($value['online'])){
					$user[$value['google_id']]['status']=1;
					$user[$value['google_id']]['online']++;
				}
				else{
					$user[$value['google_id']]['offline']++;
				}
			}
		}
		foreach($user as $key =>$value){
			$result[]=$value;
		}
		
		return Response::json(array('code'=>200,'aaData'=>$result));
		
	}
	
	public function getDeviceTable(){
	
		$unifi = new Unifi();
		$db = Database::Connect();
		$token = $db->token;
		$cursor = $token->find();
		$tmp = $unifi->getCurrentGuest(null,false);
		$device = $unifi->getUser(array('all'=>true));
		$user = array();
		$result = array();
		
		foreach($tmp as $key =>$value){
			//if()
			$authorized[$value['mac']]=$value;
		}
		foreach($cursor as $key =>$value){
			$user[$value['google_id']]=$value;
		}
		if($device){ // Online check with  
			foreach($device as $key => $value){
				if($value->is_guest == 1){
					if(isset($authorized[$value->mac])){
						$value->is_auth=1;
						if(isset($authorized[$value->mac]['google_id'])){
							$value->google_id=$authorized[$value->mac]['google_id'];
							$google = $user[$value->google_id];
							if(!isset($google['name']) || $google['name'] == '-'){
								if($google['fname'] != '-' && $google['lname'] != '-'){
									$value->name = $google['fname'].' '.$google['lname'];
								}
							}
							else $value->name = $google['name'];
						}
					}
					else{
						$value->is_auth=0;
					}
					$result[] = $value;
				}
			}	
		}
		
		return Response::json(array('code'=>200,'aaData'=>$result));
		
	}
	public function getApTable(){
	
		$unifi = new Unifi();
		$ap = $unifi->getAp();
		
		return Response::json(array('code'=>200,'aaData'=>$ap));
		
	}
	
	public function getTypeaheadDevice(){
		$search = Input::get('search');
		$regex = new MongoRegex('/'.$search.'/');
		$db = Database::Connect();
		$user = $db->user;
		$cursor = $user->find(array('$or'=>array(array('hostname'=>$regex),array('mac'=>$regex))));
		$result = array();
		foreach($cursor as $key => $value){
			$result[] = $value;
		}
		
		return $result;
	}
	
	public function getTypeaheadGoogle(){
		$search = Input::get('search');
		$regex = new MongoRegex('/'.$search.'/');
		$db = Database::Connect();
		$token = $db->token;
		$cursor = $token->find(array('$or'=>array(array('fname'=>$regex),array('lname'=>$regex),array('email'=>$regex))));
		$result = array();
		foreach($cursor as $key => $value){
			$result[] = $value;
		}
		
		return $result;
	}

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}