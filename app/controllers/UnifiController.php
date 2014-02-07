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
		if($fname != '')$guestinfo['fname']=$fname;
		if($lname != '')$guestinfo['lname']=$lname;
		if($fname != '' && $lname != '')$guestinfo['name']=$fname.' '.$lname;

		while(!$unifi->setCurrentGuest($mac,$guestinfo));
		
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
		if(!is_array($mac)){
			$mac = $unifi->getDevice($find);
			if($mac != false)$mac = $mac['mac'];
		}
		if($mac != false){
			$user = $unifi->getCurrentGuest($mac,false);
			if($user != false)return Response::json(array('code'=>200,'data'=>$user));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}
	
	public function getDeviceCount(){
		$unifi = new Unifi();
		$device  = $unifi->getDevice(array('all'=>true));
		$guest = array();
		$authorized = 0;
		$non_authorized = 0;
		if($device){
			foreach($device as $key => $value){
				if($value['is_guest'] == 1){
					if($unifi->getCurrentGuest($value['mac']))$authorized++;
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
		$device = $unifi->getDevice($find);
		$is_auth =  $unifi->getCurrentGuest($mac);
		$is_auth = $is_auth ? true : false; 
		
		// if($device != false && $device['is_guest']){
		if($device != false ){
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
			if($value['state']==1) $connected++;
			else $disconnected++;
		}
		
		return Response::json(array('code'=>200,'data'=>array('connected'=>$connected,'disconnected'=>$disconnected)));
	}

	public function getApMapCount()
	{
		$unifi = new Unifi();
		$ap = $unifi->getAp();
		$maplist = $unifi->getMapList();
		$tmplist = array();

		foreach($maplist as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$tmplist[$value['_id']] = ['name'=>$value['name'],'connected'=>0,'disconnected'=>0];
		}
		$maplist = array();

		foreach($ap as $key => $value){
			if($value['state']==1) $tmplist[$value['map_id']]['connected']++;
			else $tmplist[$value['map_id']]['disconnected']++;
		}
		foreach($tmplist as $key => $value){
			$maplist[]=$value;
		}

		return Response::json(array('code'=>200,'data'=>$maplist));
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
				if($is_auth){
					foreach($is_auth as $key => $device){
						if(isset($device['google_id'])){
							$tmp[$device['mac']]['google_id'] = $device['google_id'];
							$tmp[$device['mac']]['email'] = $device['email'];
							$tmp[$device['mac']]['auth_type'] = $device['auth_type'];
							if(!isset($device['name']) || $device['name'] == '-'){
								if($device['fname'] != '-' && $device['lname'] != '-'){
									$tmp[$device['mac']]['name'] = $device['fname'].' '.$device['lname'];
								}
							}
							else $tmp[$device['mac']]['name'] = $device['name'];
							array_push($result,$tmp[$device['mac']]);
							unset($tmp[$device['mac']]);
						}
					}
				}
				if(count($result)>0)return Response::json(array('code'=>200,'data'=>$result));
				else return Response::json(array('code'=>204 ,'data'=>array('message'=>'No user authorized to this AP.')));
			}
			else return Response::json(array('code'=>204 ,'data'=>array('message'=>'No user accessed to this AP.')));
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
	
	public function getDevicesStatReport(){

		$mac = Input::get('mac');
		$at = Input::get('at') ;
		$to = Input::get('to') ; 
		if(!is_array($mac)) return Response::json(array('code'=>404));

		$db = Database::Connect();

		$at = $at != null ? strtotime("midnight", $at) : strtotime("midnight", time());
		$to = $to != null ? strtotime("tomorrow", $to)- 1 : strtotime("tomorrow", time())- 1; 
		$result = array();

		$sessionCol = $db->session;
		$deviceCol = $db->user;
		$deviceCursor = $deviceCol->find(array('mac' => array('$in' => $mac)));
		foreach ($deviceCursor as $key => $value) {
			$value["tx_bytes"]=0;
			$value["rx_bytes"]=0;
			$value["bytes"]=0;
			$result[$value['mac']]= $value;
		}
		$sessionCursor = $sessionCol->find(array('mac' => array('$in' => $mac),'$and'=>array(array('assoc_time'=>array('$gte'=>$at)),array('assoc_time'=>array('$lte'=>$to)))));
		foreach ($sessionCursor as $key => $value) {
		 	$result[$value['mac']]['tx_bytes']+=$value['tx_bytes'];
		 	$result[$value['mac']]['rx_bytes']+=$value['rx_bytes'];
		 	$result[$value['mac']]['bytes']+=$value['bytes'];
		}

		// $tmp = array();
		// $stamp = strtotime("midnight", $at); 
		// do{
		// 	$tmp[$stamp]['date']=$stamp;
		// 	$tmp[$stamp]['tx_bytes']=0;
		// 	$tmp[$stamp]['rx_bytes']=0;
		// 	$stamp = strtotime("tomorrow", $stamp);
		// }while($stamp <= strtotime("midnight",$to));
		
		// foreach($cursor as $key => $value){
		// 	$time = strtotime("midnight", $value['assoc_time']);
		// 	$tmp[$time]['tx_bytes'] = $tmp[$time]['tx_bytes']+$value['tx_bytes'];
		// 	$tmp[$time]['rx_bytes'] = $tmp[$time]['rx_bytes']+$value['rx_bytes'];
		// }
		
		// foreach($tmp as $key => $value){
		// 	$result[] = $value;
		// }
		
		return Response::json(array('code'=>200,'data'=>$result));
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

	public function getTrafficReport()
	{	
		$time = (int)Input::get('time');
		$type = Input::get('type');
		//echo $time;
		if(($type == "hourly" || $type == "daily") && $time !=0){
			$unifi = new Unifi();
			//if($type == "daily") $time = strtotime("midnight", $time);
			// return Response::json(array('code'=>200,'data'=>$unifi->getTrafficReport($time,$type)));

			// if()
			// $db = Database::Connect();
			// $userStatistic = $db->stat->hourly->user;

		}
		else return Response::json(array('code'=>404));
	}

	public function getUserReport()
	{
		$mac = Input::get('mac');
		
		$unifi = new Unifi();
		if($mac != false){
			$user = $unifi->getCurrentGuest($mac,false);
			if($user != false)return Response::json(array('code'=>200,'data'=>$user));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
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
		$online = $unifi->getDevice(array('all'=>true));
		if($online){
			foreach($online as $key => $value){
				$tmp[$value['mac']]=$value;
			}
		}
		
		foreach($cursor as $key => $value){
			$value['_id']=(string)$value['_id'];
			if(isset($tmp[$value['mac']]) && $tmp[$value['mac']]['is_guest'] == 1){
				$value['bytes']=$tmp[$value['mac']]['bytes'];
				$value['rx_bytes']=$tmp[$value['mac']]['rx_bytes'];
				$value['tx_bytes']=$tmp[$value['mac']]['tx_bytes'];
				$value['ap_mac']=$tmp[$value['mac']]['ap_mac'];
				$value['ip']=$tmp[$value['mac']]['ip'];
				$value['map_id']=$tmp[$value['mac']]['map_id'];
				$value['uptime']=$tmp[$value['mac']]['uptime'];
				$value['bytes.r']=$tmp[$value['mac']]['bytes.r'];

				$result['online'][]=$value;
			}
			else{
				$result['offline'][]=$value;
			}
		}
		
		if(count($result)>0)return Response::json(array('code'=>200,'data'=>$result));
		else return Response::json(array('code'=>404));
	}

	public function getOnlineDeviceList(){
		$search = Input::get('search');
		$start = Input::get('start') != null ? Input::get('start') : 0;
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();
	
		$authorizedCursor = $unifi->getCurrentGuest(null,false);
		$authorizedDevice = array();
		if($authorizedCursor){
			foreach($authorizedCursor as $key =>$value){
				if(isset($value['google_id']))
					$authorizedDevice[$value['mac']]=$value;
			}
		}

		$result=[];
		$allOnlineDevice = $unifi->getDevice(array('all'=>true));
		if($allOnlineDevice){
			foreach($allOnlineDevice as $key => $value){
				if(isset($authorizedDevice[$value['mac']])){

					$value['google_id']=$authorizedDevice[$value['mac']]['google_id'];
					if(isset($authorizedDevice[$value['mac']]['name']))$value['name']=$authorizedDevice[$value['mac']]['name'];
					
					$value['email']=$authorizedDevice[$value['mac']]['email'];
					$value['is_auth']=true;
					$result[]=$value;
				}
				else{
					$value['is_auth']=false;
					$result[]=$value;
				}
			}		
		}

		function fixem($a, $b){
		  if ($a["assoc_time"] == $b["assoc_time"]) { return 0; }
		  return ($a["assoc_time"] < $b["assoc_time"]) ? -1 : 1;
		}
		function search($find, $originalArray) {
			$resultArray=[];
		   	foreach ($originalArray as $key => $value) {
		       if (stristr($value['hostname'] , $find)) {
		          	$resultArray[]=$value;
		       }
		       else if(stristr($value['ip'] , $find)){
					$resultArray[]=$value;
		       }
		       else if(stristr($value['mac'] , $find)){
		       		$resultArray[]=$value;
		       }
		       else if(isset($value['name'])){
		       		if(stristr($value['name'] , $find)){
		       			$resultArray[]=$value;
		       		}
		       }
		   }
		 
		    return $resultArray;
		}
		if($search != null)
			$result = search($search,$result);

		// Our Call to Sort the Data
		usort($result, "fixem");
		$result = array_slice($result,$start,$length);

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getOfflineDeviceList(){
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();
		
		$authorizedCursor = $unifi->getCurrentGuest(null,false);
		$authorizedDevice = array();
		if($authorizedCursor){
			foreach($authorizedCursor as $key =>$value){
				if(isset($value['google_id']))
					$authorizedDevice[$value['mac']]=$value;
			}
		}

		$result=[];
		$allOnlineDevice = $unifi->getDevice(array('all'=>true));
		$onlineMac = array();
		if($allOnlineDevice){
			foreach($allOnlineDevice as $key => $value){
				$onlineMac[] = $value['mac'];
			}		
		}

		
		$regex = new MongoRegex('/'.$search.'/i');
		$db = Database::Connect();
		$device = $db->user;
		$deviceCursor = $device->find(
			array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('hostname'=>$regex),
							array('mac'=>$regex)
						)
					),
					array(
						'mac'=>array('$nin' => $onlineMac)
					)
				)
			)
		);
		$deviceCursor->skip($start);
		$deviceCursor->limit($length);
		$deviceCursor->sort(array('_id'=>-1));

		$result=[];
		foreach($deviceCursor as $key =>$value) {
			if(isset($authorizedDevice[$value['mac']])){

				$value['google_id']=$authorizedDevice[$value['mac']]['google_id'];
				$value['name']=$authorizedDevice[$value['mac']]['name'];
				$value['email']=$authorizedDevice[$value['mac']]['email'];
				$value['is_auth']=true;
				$result[]=$value;
			}
			else{
				$value['is_auth']=false;
				$result[]=$value;
			}
		};

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getPendingDeviceList(){
		$search = Input::get('search');
		$start = Input::get('start') != null ? Input::get('start') : 0;
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();
	
		$authorizedCursor = $unifi->getCurrentGuest(null,false);
		$authorizedMac = array();
		if($authorizedCursor){
			foreach($authorizedCursor as $key =>$value){
				if(isset($value['google_id']))
					$authorizedMac[$value['mac']]=true;
			}
		}

		$result=[];
		$allOnlineDevice = $unifi->getDevice(array('all'=>true));
		if($allOnlineDevice){
			foreach($allOnlineDevice as $key => $value){
				if(!isset($authorizedMac[$value['mac']])){
					$result[]=$value;
				}
			}		
		}

		function fixem($a, $b){
		  if ($a["assoc_time"] == $b["assoc_time"]) { return 0; }
		  return ($a["assoc_time"] < $b["assoc_time"]) ? -1 : 1;
		}
		function search($find, $originalArray) {
			$resultArray=[];
		   	foreach ($originalArray as $key => $value) {
		       if (stristr($value['hostname'] , $find)) {
		          	$resultArray[]=$value;
		       }
		       else if(stristr($value['ip'] , $find)){
					$resultArray[]=$value;
		       }
		       else if(stristr($value['mac'] , $find)){
		       		$resultArray[]=$value;
		       }
		   }
		 
		    return $resultArray;
		}
		if($search != null)
			$result = search($search,$result);

		// Our Call to Sort the Data
		usort($result, "fixem");
		$result = array_slice($result,$start,$length);

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getUnauthorizedDeviceList(){
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();
	
		$authorizedCursor = $unifi->getCurrentGuest(null,false);
		$authorizedMac = array();
		if($authorizedCursor){
			foreach($authorizedCursor as $key =>$value){
				if(isset($value['google_id']))
					$authorizedMac[]=$value['mac'];
			}
		}
		
		$regex = new MongoRegex('/'.$search.'/i');
		$db = Database::Connect();
		$device = $db->user;
		$deviceCursor = $device->find(
			array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('hostname'=>$regex),
							array('mac'=>$regex)
						)
					),
					array(
						'mac'=>array('$nin' => $authorizedMac)
					)
				)
			)
		);
		$deviceCursor->skip($start);
		$deviceCursor->limit($length);
		$deviceCursor->sort(array('_id'=>-1));

		$result=[];
		foreach($deviceCursor as $key =>$value) $result[]=$value;

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getOnlineUserList(){
	
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();

		$allOnlineDevice = $unifi->getDevice(array('all'=>true));
		$onlineMac= array();
		if($allOnlineDevice){ // Online check with       Online User  and  Guest table
			foreach($allOnlineDevice as $key => $value){
				if($value['is_guest'] == 1){
					$onlineMac[] = $value['mac'];
				}
			}		
		}

		$googleId = array();
		if(count($onlineMac)>0){
			$authorizedCursor = $unifi->getCurrentGuest($onlineMac,false);
			if($authorizedCursor){
				foreach($authorizedCursor as $key =>$value){
					if(isset($value['google_id']))
						$googleId[]=$value['google_id'];
				}
			}
		}

		$regex = new MongoRegex('/'.$search.'/i');
		$db = Database::Connect();
		$token = $db->token;
		$userCursor = $token->find(
			array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('fname'=>$regex),array('lname'=>$regex),
							array('email'=>$regex)
						)
					),
					array(
						'google_id'=>array('$in' => $googleId)
					)
				)
			)
		);
		$userCursor->skip($start);
		$userCursor->limit($length);
		$userCursor->sort(array('_id'=>-1));

		$result=[];
		foreach($userCursor as $key =>$value) $result[]=$value;

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getOfflineUserList(){
	
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();

		$allOnlineDevice = $unifi->getDevice(array('all'=>true));
		$onlineMac= array();
		if($allOnlineDevice){ // Online check with       Online User  and  Guest table
			foreach($allOnlineDevice as $key => $value){
				if($value['is_guest'] == 1){
					$onlineMac[] = $value['mac'];
				}
			}		
		}

		$googleId = array();
		if(count($onlineMac)>0){
			$authorizedCursor = $unifi->getCurrentGuest($onlineMac,false);
			if($authorizedCursor){
				foreach($authorizedCursor as $key =>$value){
					if(isset($value['google_id']))
						$googleId[]=$value['google_id'];
				}
			}
		}

		$regex = new MongoRegex('/'.$search.'/i');
		$db = Database::Connect();
		$token = $db->token;
		$userCursor = $token->find(
			array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('fname'=>$regex),array('lname'=>$regex),
							array('email'=>$regex)
						)
					),
					array(
						'google_id'=>array('$nin' => $googleId)
					)
				)
			)
		);
		$userCursor->skip($start);
		$userCursor->limit($length);
		$userCursor->sort(array('_id'=>-1));

		$result=[];
		foreach($userCursor as $key =>$value) $result[]=$value;

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getUserList(){
	
		$unifi = new Unifi();
		$db = Database::Connect();
		$token = $db->token;
		$cursor = $token->find();
		$user = array();
		$result = array();
		$guest_tmp = $unifi->getCurrentGuest(null,false);
		$online = $unifi->getDevice(array('all'=>true));
		
		foreach($guest_tmp as $key =>$value){
			$guest[$value['mac']]=$value;
		}
		
		if($online){ // Online check with       Online User  and  Guest table
			foreach($online as $key => $value){
				if($value['is_guest'] == 1){
					if(isset($guest[$value['mac']])){
						$guest[$value['mac']]['online']=true;
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
				//$tmp = array('auth_type'=>$value['auth_type'],'mac'=>$value['mac'],'start'=>$value['start'],'end'=>$value['end']);	
				//if(isset($value['hostname'])) $tmp['hostname'] = $value['hostname'];
				//$tmp['picture'] = $value['hostname'];

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
		
		return Response::json(array('code'=>200,'data'=>$result));
	}
	
	public function getDeviceList(){
	
		$unifi = new Unifi();
		$db = Database::Connect();
		$token = $db->token;
		$cursor = $token->find();
		$tmp = $unifi->getCurrentGuest(null,false);
		$device = $unifi->getDevice(array('all'=>true));
		$user = array();
		$result = array();
		
		foreach($tmp as $key =>$value){			
			$authorized[$value['mac']]=$value;
		}
		foreach($cursor as $key =>$value){
			$user[$value['google_id']]=$value;
		}
		if($device){ // Online check with  
			foreach($device as $key => $value){
				if($value['is_guest'] == 1){
					$value['is_online'] = 1;
					if(isset($authorized[$value['mac']])){
						$value['is_auth'] = 1;
						if(isset($authorized[$value['mac']]['google_id'])){
							$value['google_id']=$authorized[$value['mac']]['google_id'];
							$google = $user[$value['google_id']];
							if(!isset($google['name']) || $google['name'] == '-'){
								if($google['fname'] != '-' && $google['lname'] != '-'){
									$value['name'] = $google['fname'].' '.$google['lname'];
								}
							}
							else $value['name'] = $google['name'];
						}
					}
					else{
						$value['is_auth']=0;
					}
					$result[] = $value;
				}
			}	
		}
		
		return Response::json(array('code'=>200,'data'=>$result));
		
	}
	public function getDeviceListNewPrototype(){
	
		$unifi = new Unifi();
		$db = Database::Connect();
		$tokenCol = $db->token;
		$deviceCol = $db->user;

		$tokenCursor = $tokenCol->find();
		$deviceCursor = $deviceCol->find();

		 
		$tmp = $unifi->getCurrentGuest(null,false);
		$onlineDevice = $unifi->getDevice(array('all'=>true));
		$user = array();
		$device = array();
		$result = array();
		
		foreach($deviceCursor as $key => $value){ //All Device
			unset($value['is_guest']);
			$device[$value['mac']] = $value;
		}
		if($onlineDevice){
			foreach($onlineDevice as $key =>$value){
				$device[$value['mac']] = $value;
			}
		}

		foreach($tmp as $key =>$value){			  //Authorized Device
			$authorized[$value['mac']]=$value;
		}
		foreach($tokenCursor as $key =>$value){   
			$user[$value['google_id']]=$value;
		}

		if(count($device)>0){ // Online check with  
			foreach($device as $key => $value){
				if(isset($value['is_guest'])){
					$value['is_online']=1;
				}
				else{
					$value['is_online']=0;
				}

				if(isset($authorized[$value['mac']])){
					$value['is_auth']=1;
					if(isset($authorized[$value['mac']]['google_id'])){
						$value['google_id']=$authorized[$value['mac']]['google_id'];
						$google = $user[$value['google_id']];
						if(!isset($google['name']) || $google['name'] == '-'){
							if($google['fname'] != '-' && $google['lname'] != '-'){
								$value['name'] = $google['fname'].' '.$google['lname'];
							}
						}
						else $value['name'] = $google['name'];
					}
				}
				else{
					$value['is_auth']=0;
				}
				
				$result[] = $value;
			}	
		}
		
		return Response::json(array('code'=>200,'data'=>$result));
		
	}

	public function getApList(){
	
		$unifi = new Unifi();
		$ap = $unifi->getAp();
		
		return Response::json(array('code'=>200,'data'=>$ap));
		
	}
	
	public function getTypeaheadDevice(){
		$search = Input::get('search');
		$regex = new MongoRegex('/'.$search.'/i');
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
		$regex = new MongoRegex('/'.$search.'/i');
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