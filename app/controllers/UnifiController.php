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
	public function getSumBytes(){

		$unifi = new Unifi();
		$device  = $unifi->getDevice(array('all'=>true));
		$guest = array();
		$sum = 0;
		if($device){
			foreach($device as $key => $value){
				$sum += $value['tx_bytes']+$value['rx_bytes'];
			}	
		}
		return $sum;
	
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
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		
		$mac = Input::get('mac');
		
		$find['mac']=$mac;
		$db = Database::Connect();
		$user = $db->user;
		$userGroup = $db->usergroup;
		$groupCursor = $userGroup->find();
		$group=array();

		foreach ($groupCursor as $key => $value) {
			$value['_id'] = (string)$value['_id'];
			$group[$value['_id']] = $value;
		}

		$unifi = new Unifi();
		$device = $unifi->getDevice($find);
		$is_auth =  $unifi->getCurrentGuest($mac);
		$is_auth = $is_auth ? true : false; 
		
		// if($device != false && $device['is_guest']){
		if($device != false ){
			if(isset($device['usergroup_id'])){
				$device['usergroup_name'] = $group[$device['usergroup_id']]['name'];
			}
			else{
				$device['usergroup_name'] = 'Default';
			}
			$device['is_auth']=$is_auth;

			$result = array('code'=>200,'data'=>$device);

		}
		else{
			$device = $user->findOne($find);
			if(isset($device['usergroup_id']) && $device['usergroup_id'] != 'null'){
				$device['usergroup_name'] = $group[$device['usergroup_id']]['name'];
			}
			else{
				$device['usergroup_name'] = 'Default';
			}
			$device['is_auth']=$is_auth;

			$result = array('code'=>206,'data'=>$device);
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
			if(isset($value['map_id'])){
				if($value['state']==1) $tmplist[$value['map_id']]['connected']++;
				else $tmplist[$value['map_id']]['disconnected']++;
			}
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
		$device = $unifi->getDevice(array('all'=>true));

		if( $mac != null &&  $device != false){
			$find = array();
			$normal = array();
			$auth = array();
			$result = array();

			foreach($device as $key => $value){
				if($value['ap_mac']==$mac){
					$find[] = $value['mac'];
					$normal[$value['mac']]=$value;
				}
			}
			if(count($find)>0){
				$is_auth =  $unifi->getCurrentGuest($find,false);
				if($is_auth){
					foreach($is_auth as $key => $device){
						if(isset($device['google_id'])){
							$normal[$device['mac']]['google_id'] = $device['google_id'];
							$normal[$device['mac']]['email'] = $device['email'];
							$normal[$device['mac']]['auth_type'] = $device['auth_type'];
							if(!isset($device['name']) || $device['name'] == '-'){
								if($device['fname'] != '-' && $device['lname'] != '-'){
									$normal[$device['mac']]['name'] = $device['fname'].' '.$device['lname'];
								}
							}
							else $normal[$device['mac']]['name'] = $device['name'];
							$auth[]=$normal[$device['mac']];
							unset($normal[$device['mac']]);
						}
					}
				}
				function fixem($a, $b){
				  if ($a["bytes"] == $b["bytes"]) { return 0; }
				  return ($a["bytes"] < $b["bytes"]) ? 1 : -1;
				}
				usort($auth, "fixem");
				usort($normal, "fixem");
				$result['auth']= $auth;
				$result['not_auth'] = array();
				foreach ($normal as $key => $value) {
					$result['not_auth'][] = $value;
				}
				if(count($result['auth'])+count($result['not_auth'])>0)return Response::json(array('code'=>200,'data'=>$result));
				else return Response::json(array('code'=>204 ,'data'=>array('message'=>'No user authorized to this AP.')));
			}
			else return Response::json(array('code'=>204 ,'data'=>array('message'=>'No user accessed to this AP.')));
		}
		else return Response::json(array('code'=>404));
	}

	public function getApRatio()
	{	
		$db = Database::Connect();
		$user = $db->user;

		$unifi = new Unifi();
		$ap = $unifi->getAp();
		$device = $unifi->getDevice(array('all'=>true));

		if($ap){

			$count = array();
			$traffic = array();
			$wlan = array();
			$result = array();

			foreach($ap as $key => $value){
				$count[$value['mac']]['name']=$value['name'];
				$count[$value['mac']]['count']=0;
				$traffic[$value['mac']]['name']=$value['name'];
				$traffic[$value['mac']]['byes']=0;
			}
			foreach($device as $key => $value){
				$count[$value['ap_mac']]['count']++;
				$traffic[$value['ap_mac']]['byes']+=$value['bytes'];
			}
			
			$result['count'] = $count;
			$result['traffic'] = $traffic;
			$result['wlan'] = $wlan;

			if(count($result)>0)
				return Response::json(array('code'=>200,'data'=>$result));
			else
				return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}

	public function getCurrentUsage()
	{	
		$db = Database::Connect();

		$unifi = new Unifi();
		$ap = $unifi->getAp();

		function fixem($a, $b){
		  if ($a['value'] == $b['value']) { return 0; }
		  return ($a['value'] < $b['value']) ? 1 : -1;
		}

		$count = array();
		$traffic = array();
		$wlan = array();
		$result = array();

		if($ap){

			$time = time();
			$time = $time-($time%3600);
			foreach($ap as $key => $value){
				$cursor = $db->selectCollection('stat.hourly.ap.'.$value['mac'])->findOne(array('datetime'=> new MongoDate($time)));
				if($cursor){
					$count[] = array('name'=>$value['name'],
									 'mac'=>$value['mac'],
									 'value'=>isset($cursor['num_sta']) ? $cursor['num_sta'] : 0);
					$traffic[] = array('name'=>$value['name'],
									 'mac'=>$value['mac'],
									 'value'=>$cursor['bytes']);
				}
			}

		}

		$device = $unifi->getDevice(array('all'=>true));
		if($device){
			$cursor = $db->wlanconf->find();
			$tmp = array();
			foreach ($cursor as $key => $value) {
				$tmp[$value['name']] = array('name'=>$value['name'],
											  'value'=>0);
			}
			foreach($device as $key => $value){
				if(isset($tmp[$value['essid']]))
					$tmp[$value['essid']]['value']++;
				else{
					$tmp[$value['essid']]['name']=$value['essid'];
					$tmp[$value['essid']]['value']=1;
				}
			}
			foreach ($tmp as $key => $value) {
				$wlan[]=$value;
			}
		}

		usort($count, 'fixem');
		usort($traffic, 'fixem');
		usort($wlan, 'fixem');		

		$result['count'] = $count;
		$result['traffic'] = $traffic;
		$result['wlan'] = $wlan;
		return Response::json(array('code'=>200,'data'=>$result));
	}
	
	public function getMapList()
	{
		$id = Input::get('id');
		$unifi = new Unifi();
		$ap = $unifi->getAp();
		$maplist = $unifi->getMapList($id);
		$tmplist = array();
		if($maplist){
			foreach($maplist as $key => $value){
				$value['_id'] = (string)$value['_id'];
				$tmplist[$value['_id']] = ['_id'=>$value['_id'],'name'=>$value['name'],"file_id" =>$value['file_id'],"device_count" =>0,'ap'=>['connected'=>0,'disconnected'=>0]];
			}
			$maplist = array();

			foreach($ap as $key => $value){

				if(isset($value['map_id'])){
					if(isset($value['num_sta']))$tmplist[$value['map_id']]['device_count'] += $value['num_sta'];
					if($value['state']==1) $tmplist[$value['map_id']]['ap']['connected']++;
					else $tmplist[$value['map_id']]['ap']['disconnected']++;
				}
			}
			foreach($tmplist as $key => $value){
				$maplist[]=$value;
			}

			return Response::json(array('code'=>200,'data'=>$maplist));
		}
		else return Response::json(array('code'=>404));
	}

	public function getMapApList()
	{
		$id = Input::get('id');
		$unifi = new Unifi();
		$ap = $unifi->getAp();
		$maplist = $unifi->getMapList($id);
		$tmplist = array();
		if($maplist){
			foreach($maplist as $key => $value){
				$value['_id'] = (string)$value['_id'];
				$tmplist[$value['_id']] = array('_id'=>$value['_id'],'ap'=>array());
			}
			$maplist = array();

			foreach($ap as $key => $value){
				if(isset($value['map_id'])){
					$value['a'] = (float)$value['x'];
					$value['y'] = (float)$value['y'];
					$tmplist[$value['map_id']]['ap'][] = $value;
				}
			}
			foreach($tmplist as $key => $value){

				$maplist[]=$value;
			}

			return Response::json(array('code'=>200,'data'=>$maplist));
		}
		else return Response::json(array('code'=>404));
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
		$at = Input::get('at') != null ? Input::get('at') : time();
		$to = Input::get('to') != null ? Input::get('to') : time(); 
		
		function fixem($a, $b){
		  if ($a['datetime']->sec == $b['datetime']->sec) { return 0; }
		  return ($a['datetime']->sec < $b['datetime']->sec) ? -1 : 1;
		}

		$db = Database::Connect();
		$userStatistic = $db->stat->daily->user;
		
		$cursor = $userStatistic->find( 
			array('$and' => 
				array(
					array('datetime' => array('$gte' => new MongoDate($at-($at%86400)))),
					array('datetime' => array('$lte' => new MongoDate($to))),
					array('user.mac' => $mac)
				)
			)
		,array('user'=>1,'datetime'=>1));

		$unifi = new Unifi();
		$resultTmp = array();
		while($at <= $to ){
			$resultTmp[$at-($at%86400)] = array(
				"_id"=>null,
				"datetime"=>new MongoDate($at-($at%86400)),
				'tx_bytes'=>0,
				'rx_bytes'=>0,
				"bytes"=>0,
				"bytes.r"=>0,
			);

			$at = strtotime("next day",$at);
		}
		foreach ($cursor as $key => $value) {
			;
			for($i=0;$i<count($value['user']);$i++){
				if($value['user'][$i]['mac'] == $mac){

					$resultTmp[$value['datetime']->sec]['_id'] = (string)$value['_id'];
					$resultTmp[$value['datetime']->sec]['datetime'] = 	$value['datetime'];
					$resultTmp[$value['datetime']->sec]['tx_bytes'] = 	$value['user'][$i]['tx_bytes'];
					$resultTmp[$value['datetime']->sec]['rx_bytes'] =	$value['user'][$i]['rx_bytes'];
					$resultTmp[$value['datetime']->sec]['bytes']	=	$value['user'][$i]['bytes'];
					$resultTmp[$value['datetime']->sec]['bytes.r']	=	$value['user'][$i]['bytes.r'];
				}
			}
		}
		foreach ($resultTmp as $key => $value) {
			$result[]=$value;
		}
	
		usort($result, "fixem");
	    return Response::json(array('code'=>200,'data'=>$result));
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

		function fixem($a, $b){
		  if ($a['datetime']->sec == $b['datetime']->sec) { return 0; }
		  return ($a['datetime']->sec < $b['datetime']->sec) ? -1 : 1;
		}

		if(($type == "hourly" || $type == "daily") && $time != 0){

			$result = array();
			$resultTmp = array();
			$unifi = new Unifi();
			//if($type == "daily") $time = strtotime("midnight", $time);
			// return Response::json(array('code'=>200,'data'=>$unifi->getTrafficReport($time,$type)));

			if($type=='hourly'){
				$timeIndex = strtotime('-6 hours',$time);
				while($timeIndex <= $time){
					$resultTmp[$timeIndex-($timeIndex%3600)] = array("_id"=>null,"datetime"=>new MongoDate($timeIndex-($timeIndex%3600)),"bytes"=>0,"bytes.r"=>0,"user_count"=>0);
					$timeIndex = strtotime("next hours",$timeIndex);
				}
				
				$db = Database::Connect();
				$userStatistic = $db->stat->hourly->user;
				
				$cursor = $userStatistic->find( 
					array('$and' => 
						array(
							array('datetime' => array('$gte' => new MongoDate(strtotime('-7 hours',$time)))),
							array('datetime' => array('$lte' => new MongoDate($time) )) 
						)
					)
				,array('user'=>0));
				foreach ($cursor as $key => $value) {
					$value['_id'] = (string)$value['_id'];
					$resultTmp[$value['datetime']->sec]=$value;
				}
				foreach ($resultTmp as $key => $value) {
					$result[]=$value;
				}
				
				
			}
			else{
				$timeIndex = strtotime('-6 day',$time);
				while($timeIndex <= $time){
					$resultTmp[$timeIndex-($timeIndex%86400)] = array("_id"=>null,"datetime"=>new MongoDate($timeIndex-($timeIndex%86400)),"bytes"=>0,"bytes.r"=>0,"user_count"=>0);
					$timeIndex = strtotime("next day",$timeIndex);
				}
				
				$db = Database::Connect();
				$userStatistic = $db->stat->daily->user;
				
				$cursor = $userStatistic->find( 
					array('$and' => 
						array(
							array('datetime' => array('$gte' => new MongoDate(strtotime('-7 day',$time)))),
							array('datetime' => array('$lte' => new MongoDate($time) )) 
						)
					)
				,array('user'=>0));
				foreach ($cursor as $key => $value) {
					$value['_id'] = (string)$value['_id'];
					$resultTmp[$value['datetime']->sec]=$value;
				}
				foreach ($resultTmp as $key => $value) {
					$result[]=$value;
				}
			}
			usort($result, "fixem");
			return Response::json(array('code'=>200,'data'=>$result));

		}
		else return Response::json(array('code'=>404));
	}

	public function getTrafficUserReport()
	{	
		$time = (int)Input::get('time');
		$type = Input::get('type');

		function fixem($a, $b){
		  if ($a['bytes'] == $b['bytes']) { return 0; }
		  return ($a['bytes'] < $b['bytes']) ? 1 : -1;
		}

		if(($type == "hourly" || $type == "daily") && $time != 0){

			$result = array();
			$resultTmp = array();
			$unifi = new Unifi();
			//if($type == "daily") $time = strtotime("midnight", $time);
			// return Response::json(array('code'=>200,'data'=>$unifi->getTrafficReport($time,$type)));

			if($type=='hourly'){	
				$db = Database::Connect();
				$userStatistic = $db->stat->hourly->user;
				
				$cursor = $userStatistic->findOne( 
					array( 'datetime' =>new MongoDate($time-($time%3600))
					)
				,array('user'=>1));
				if(isset($cursor['user'])){
					foreach ($cursor['user'] as $key => $value) {
						$result[]=$value;
					}
				}
			}
			else{
				$db = Database::Connect();
				$userStatistic = $db->stat->daily->user;
				
				$cursor = $userStatistic->findOne( 
					array( 'datetime' =>new MongoDate($time-($time%86400))
					)
				,array('user'=>1));
				if(isset($cursor['user'])){
					foreach ($cursor['user'] as $key => $value) {
						$result[]=$value;
					}
				}
			}
			usort($result, "fixem");
			if(count($result)>0)return Response::json(array('code'=>200,'data'=>$result));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}

	public function getTopTenUserReport()
	{	
		$time = (int)Input::get('time');
		$type = Input::get('type');

		function fixem($a, $b){
		  if ($a['bytes'] == $b['bytes']) { return 0; }
		  return ($a['bytes'] < $b['bytes']) ? 1 : -1;
		}

		if(($type == "hourly" || $type == "daily") && $time != 0){

			$result = array();
			$resultTmp = array();
			$unifi = new Unifi();
			//if($type == "daily") $time = strtotime("midnight", $time);
			// return Response::json(array('code'=>200,'data'=>$unifi->getTrafficReport($time,$type)));

			if($type=='hourly'){	
				$db = Database::Connect();
				$userStatistic = $db->stat->hourly->user;
				
				$cursor = $userStatistic->findOne( 
					array( 'datetime' =>new MongoDate($time-($time%3600))
					)
				,array('user'=>1));
				if(isset($cursor['user'])){
					foreach ($cursor['user'] as $key => $value) {
						$resultTmp[]=$value;
					}
				}
			}
			else{
				$db = Database::Connect();
				$userStatistic = $db->stat->daily->user;
				
				$cursor = $userStatistic->findOne( 
					array( 'datetime' =>new MongoDate($time-($time%86400))
					)
				,array('user'=>1));
				if(isset($cursor['user'])){
					foreach ($cursor['user'] as $key => $value) {
						$resultTmp[]=$value;
					}
				}
			}
			usort($resultTmp, "fixem");
			$count=1;
			foreach ($resultTmp as $key => $value) {
				if($count>10)break;
				$result[] = $value;
				$count++;
			}

			if(count($result)>0)return Response::json(array('code'=>200,'data'=>$result));
			else return Response::json(array('code'=>404));
		}
		else return Response::json(array('code'=>404));
	}

	public function getUserForNetmon()
	{	
		$ip = Input::get('ip');

		$unifi = new Unifi();
		$db = Database::Connect();
		$userStatistic = $db->stat->hourly->user;
		

		$mac = $unifi->getDevice(array('ip'=>$ip));
		if($mac != false){
			$mac = $mac['mac'];
			$user = $unifi->getCurrentGuest($mac,false);
			if($user != false && isset($user[0]['google_id']))return Response::json(array('code'=>200,'data'=>$user));
			else { // Same Method
				$cursor = $userStatistic->find(	array(
													'user'=>array('$elemMatch'=>array('ip'=>$ip,'is_auth'=>true))
												),
												array('user'=> 1,'datetime'=>1)
											   );
				$cursor->sort(array('datetime'=>-1));
				$device = $cursor->getNext();
				if($device){
					foreach($device['user'] as $key => $value){
						if($value['ip']==$ip) return Response::json(array('code'=>201,'datetime'=>$device['datetime'],'data'=>array($value)));
					}
				}
				else return Response::json(array('code'=>404));
			}
		}
		else { // Same Method
			$cursor = $userStatistic->find(	array(
												'user'=>array('$elemMatch'=>array('ip'=>$ip,'is_auth'=>true))
											),
											array('user'=> 1,'datetime'=>1)
										   );
			$cursor->sort(array('datetime'=>-1));
			$device = $cursor->getNext();
			if($device){
				foreach($device['user'] as $key => $value){
					if($value['ip']==$ip) return Response::json(array('code'=>201,'datetime'=>$device['datetime'],'data'=>array($value)));
				}
			}
			else return Response::json(array('code'=>404));
		}
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
				$value['bytes']=0;
				$value['rx_bytes']=0;
				$value['tx_bytes']=0;
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
		       else if(isset($value['ip'])){
					if(stristr($value['ip'] , $find))
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

	public function getBlockedDeviceList(){
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;
		
		$unifi = new Unifi();
	
		$result=[];
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
						'blocked'=>true
					)
				)
			)
		);

		$deviceCursor->skip($start);
		$deviceCursor->limit($length);
		$deviceCursor->sort(array('_id'=>-1));

		$result=[];
		foreach($deviceCursor as $key =>$value) {
			$result[]=$value;
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
		       else if(isset($value['ip'])){
					if(stristr($value['ip'] , $find))
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

		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$unifi = new Unifi();
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
					)
				)
			)
		);
		$userCursor->skip($start);
		$userCursor->limit($length);
		$userCursor->sort(array('_id'=>-1));
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

		foreach($userCursor as $key => $value){
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
			if(isset($value['google_id']) && isset($user[$value['google_id']])){
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

	public function getGoogleAccount(){
		$google_id = Input::get('google_id');
		$db = Database::Connect();
		$token = $db->token;
		$result = $token->findOne(array('google_id'=>$google_id));

		if(isset($result))return Response::json(array('code'=>200,'data'=>$result));
		else return Response::json(array('code'=>404));
	}

	public function getSettingsInformation(){
		$token_id = Input::get('token_id');
		$db = Database::Connect();
		$token = $db->token->ios;
		$tokenCursor = $token->findOne(array('token_id'=>$token_id));
		$result=array();
		$result['notification']=0;

		if(isset($tokenCursor)){
			$result['token'] = $tokenCursor;
		}
		else {
			$result['token'] = null;
		}
	
		$usergroup = $db->usergroup;
		$usergroupCursor = $usergroup->find();
		$usergroupCursor->sort(array('_id'=>1));
		foreach ($usergroupCursor as $key => $value) {
			$value['_id'] = (string)$value['_id'];
			$result['usergroup'][] = $value;
		}

		$alarm = $db->alarm;
		$alarmCursor = $alarm->find(array('read'=>array('$nin'=>array($token_id))));
		foreach ($alarmCursor as $key => $value) {
			$result['notification']++;
		}

		$wlan = $db->wlanconf;
		$wlanCursor = $wlan->find();
		$wlanCursor->sort(array('_id'=>1));

		foreach ($wlanCursor as $key => $value ){
			$value['_id'] = (string)$value['_id'];
			$result['wlan'][] = $value;
		}

		$setting = $db->setting;
		$settingCursor = $setting->findOne(array('key'=>'load_balance'));
		$settingCursor['_id'] = (string)$settingCursor['_id'];
		$result['load_balance'] = $settingCursor;


		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getNotificationList(){
		$token_id = Input::get('token_id');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$db = Database::Connect();
		$alarm = $db->alarm;
		$ap    = $db->device;
		$apCursor = $ap->find(array(),array('name'=>1,'mac'=>1));
		$apList = array();
		foreach ($apCursor as $key => $value) {
			$value['_id'] = (string)$value['_id']; 
			$apList[$value['mac']] = $value;
		}
		$alarmCursor = $alarm->find(array(),array('read'=>0,'msg'=>0));
		$alarmCursor->skip($start);
		$alarmCursor->limit($length);
		$alarmCursor->sort(array('_id'=>-1));

		$result = array();
		foreach ($alarmCursor as $key => $value) {
			if($value['key']=="EVT_AP_Lost_Contact"){
				if(isset($apList[$value['ap']]))
					if(isset($apList[$value['ap']]['name']))
						$value['ap_name'] = $apList[$value['ap']]['name'];
			}

			$value['_id'] = (string)$value['_id']; 
			$result[]=$value;
		}

		$alarm->update(
						array('read'  => array('$nin'=>array($token_id))),
						array('$push' => array('read'=>$token_id)),
						array('multiple' => true)
				);

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getNotification(){
		$token_id = Input::get('token_id');

		$db = Database::Connect();
		$alarm = $db->alarm;
		$usergroupCursor = $alarm->find(array('read'=>array('$nin'=>array($token_id))));
		$result = array();
		$result['notification']=0;
		foreach ($usergroupCursor as $key => $value) {
			$result['notification']++;
		}

		return Response::json(array('code'=>200,'data'=>$result));
	}

	public function getIosToken(){
		$token_id = Input::get('token_id');
		$db = Database::Connect();
		$token = $db->token->ios;
		$result = $token->findOne(array('token_id'=>$token_id));

		if(isset($result)){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function getGroup(){
		$id = Input::get('id');
		$db = Database::Connect();
		$usergroup = $db->usergroup;

		$usergroupCursor = $usergroup->find(array('_id'=> new MongoId($id)));
		$result=array();
		foreach ($usergroupCursor as $key => $value) {
			$value['_id'] = (string)$value['_id'];
			$result[] = $value;
		}

		if(count($result)>0){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function getDeviceInGroup(){
		$id = Input::get('id');
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$db = Database::Connect();
		$user = $db->user;
		$regex = new MongoRegex('/'.$search.'/i');

		$userCursor = $user->find(array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('hostname'=>$regex),
							array('mac'=>$regex)
						)
					),
					array('usergroup_id'=> $id)
				)
		));
		$userCursor->skip($start);
		$userCursor->limit($length);
		$userCursor->sort(array('_id'=>-1));

		$result=array();
		foreach ($userCursor as $key => $value) {
			$value['_id'] = (string)$value['_id'];
			$result[] = $value;
		}

		if(count($result)>0){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function getDeviceForAdding(){
		$id = Input::get('id');
		$search = Input::get('search');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$db = Database::Connect();
		$user = $db->user;
		$regex = new MongoRegex('/'.$search.'/i');

		$userCursor = $user->find(array(
				'$and'=>array(
					array(
						'$or'=>array(
							array('hostname'=>$regex),
							array('mac'=>$regex)
						)
					),
					array('usergroup_id'=> array('$ne'=>$id))
				)
		));
		$userCursor->skip($start);
		$userCursor->limit($length);
		$userCursor->sort(array('_id'=>-1));

		$result=array();
		foreach ($userCursor as $key => $value) {
			$value['_id'] = (string)$value['_id'];
			$result[] = $value;
		}

		if(count($result)>0){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}

	}

	public function postNotification(){
		$token_id = Input::get('token_id');

		$db = Database::Connect();
		$alarm = $db->alarm;
		$cursor = $alarm->update(
									array('read'  => array('$nin'=>array($token_id))),
									array('$push' => array('read'=>$token_id)),
									array('multiple' => true)
								);
		if($cursor){
			return Response::json(array('code'=>200,'data'=>array('message'=>'Read all notification.')));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}
	public function getClearNotification(){
		$token_id = Input::get('token_id');

		$db = Database::Connect();
		$alarm = $db->alarm;
		$cursor = $alarm->update(
									array('read'  => array('$in'=>array($token_id))),
									array('$pop' => array('read'=>$token_id)),
									array('multiple' => true)
								);
		if($cursor){
			return Response::json(array('code'=>200,'data'=>array('message'=>'Read all notification.')));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function getDeviceSessionHistory(){
		$mac = Input::get('mac');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$db = Database::Connect();
		$session = $db->session;
		$sessionCursor = $session->find(array('mac'=>$mac));
		$sessionCursor->skip($start);
		$sessionCursor->limit($length);
		$sessionCursor->sort(array('_id'=>-1));

		$result=array();
		foreach($sessionCursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$value['user_id'] = (string)$value['user_id'];
			$value['start'] = (string)$value['assoc_time'];
			$value['end'] = (string)$value['disassoc_time'];
			$result[] =$value;
		}

		if(count($result)>0){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function getDeviceAuthHistory(){
		$mac = Input::get('mac');
		$start = Input::get('start');
		$length = Input::get('length') != null ? Input::get('length') : 0;

		$db = Database::Connect();
		$guest = $db->guest;
	
		
		$guestCursor = $guest->find(array('mac' => $mac,'google_id'=>array('$exists'=>true)));
		$guestCursor->skip($start);
		$guestCursor->limit($length);
		$guestCursor->sort(array('_id'=>-1));

		$result=array();
		foreach($guestCursor as $key => $value){
			$value['_id'] = (string)$value['_id'];

			$result[] =$value;
		}

		if(count($result)>0){
			return Response::json(array('code'=>200,'data'=>$result));
		}
		else {
			return Response::json(array('code'=>404));
		}

	}

	public function postChangeDeviceToGroup(){
		$group_id = Input::get('group_id') != "null" ? Input::get('group_id')  : null;
		$user_id = Input::get('user_id');

		$unifi = new Unifi();
		$result = $unifi->sendChangeDeviceToGroup($user_id,array(
						"note"=>"",
						"noted"=>false,
						"usergroup_id"=>$group_id
				));
		if(count($result['data'])>0){
			return Response::json(array('code'=>200,'data'=>array('message'=>'Change group complete.')));
		}
		else {
			return Response::json(array('code'=>404));
		}
	}

	public function postDeleteDeviceFromGroup(){
		$mac = Input::get('mac');
		$db = Database::Connect();
		$user = $db->user;

		$userCursor = $user->update(array('mac'=>$mac),array('$set'=>array('usergroup_id'=>null)));

		if($userCursor){
			return Response::json(array('code'=>200));
		}
		else {
			return Response::json(array('code'=>404));
		}

	}

	public function postIosToken(){
		$token_id = Input::get('token_id');
		$enabled = Input::get('enabled');
		$db = Database::Connect();
		$token = $db->token->ios;
		$result = $token->findOne(array('token_id'=>$token_id));

		if(isset($result)){
			$find = array("last_seen"=>time(),'token_id'=>$token_id);
			if($enabled != '')$find['enabled'] = (bool)$enabled; 
			$status = $token->update(array('token_id'=>$token_id), array('$set'=>$find));
			if($status)return Response::json(array('code'=>200,'data'=>array('message'=>'Update Token.')));
			else return Response::json(array('code'=>404));
		}
		else {
			$status = $token->insert(array("first_seen"=>time(),"last_seen"=>time(),'token_id'=>$token_id,'enabled'=>true));
			if($status)return Response::json(array('code'=>200,'data'=>array('message'=>'Insert New Token.')));
			else return Response::json(array('code'=>404));
		}
	}

	public function postGroup(){
		$id 					= Input::get('id');
		$name   				= Input::get('name') != null ? Input::get('name') : 'Unknown';
		$qos_rate_max_down 		= (int)Input::get('qos_rate_max_down') > 0 ? (int)Input::get('qos_rate_max_down') : -1;
		$qos_rate_max_up		= (int)Input::get('qos_rate_max_up') > 0 ? (int)Input::get('qos_rate_max_up') : -1;

		$unifi = new Unifi();
		$db = Database::Connect();
		$usergroup = $db->usergroup;
		$usergroupCursor = $usergroup->findOne(array('_id'=> new MongoId($id)));
		if($usergroupCursor){
			$result = $unifi->sendEditGroup($id,array(
							"name"=>$name,
							"downRate_enabled"=>$qos_rate_max_down != -1,
							"qos_rate_max_down"=>$qos_rate_max_down ,
							"upRate_enabled"=>$qos_rate_max_up != -1,
							"qos_rate_max_up"=>$qos_rate_max_up
					));

			if(count($result['data'])>0){
				return Response::json(array('code'=>200,'data'=>array('message'=>'Update UserGroup.')));
			}
			else {
				return Response::json(array('code'=>404));
			}
		}
		else{
			$result = $unifi->sendAddGroup(array(
							"name"=>$name,
							"downRate_enabled"=>$qos_rate_max_down != -1,
							"qos_rate_max_down"=>$qos_rate_max_down ,
							"upRate_enabled"=>$qos_rate_max_up != -1,
							"qos_rate_max_up"=>$qos_rate_max_up
					));

			if(count($result['data'])>0){
				return Response::json(array('code'=>200,'data'=>array('message'=>'Insert UserGroup.')));
			}
			else {
				return Response::json(array('code'=>404));
			}
		}
	}

	public function postDeleteGroup(){
		$id = Input::get('id');

		$unifi = new Unifi();
		$db = Database::Connect();
		$user = $db->user;
		$userCursor = $user->update(array('usergroup_id'=> $id),array('$set'=>array('usergroup_id'=>null)));

		$result = $unifi->sendDeleteGroup($id);
		if($result['meta']['rc']=='ok'){
			return Response::json(array('code'=>200,'data'=>array('message'=>'Delete UserGroup.')));
		}
		else {
			return Response::json(array('code'=>404));
		}

	}

	public function postLoadBalancing(){
		$enabled 				= (bool)Input::get('enabled');
		$max_sta 				= (int)Input::get('max_sta') > 0 ? (int)Input::get('max_sta') : -1;

		$unifi = new Unifi();
		$result = $unifi->sendEditLoadBalancing(array(
						"enabled"=>$enabled,
						"max_sta"=>$max_sta 
				));
		if(count($result['data'])>0){
			return Response::json(array('code'=>200,'data'=>array('message'=>'Update Load Balancing.')));
		}
		else {
			return Response::json(array('code'=>404));
		}
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
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnAuthorization($mac);
		return 1;
	}
	public function postReconnect()
	{
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendReconnect($mac);
		return 1;
	}
	public function postRestartAp()
	{
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$data = $unifi->sendRestartAp($mac);
		if(count($data['data'])>0) return Response::json(array('code'=>200,'data'=>$data['data']));
		else return Response::json(array('code'=>404));
	}
	public function postEditApName()
	{
		$id = Input::get('id');
		$name = Input::get('name');
		$unifi = new Unifi();
		$data = $unifi->sendEditApName($id,$name);
		if(count($data['data'])>0) return Response::json(array('code'=>200,'data'=>$data['data']));
		else return Response::json(array('code'=>404));
		
	}
	public function postBlock()
	{
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendBlock($mac);
		return 1;
	}
	public function postUnBlock()
	{
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		$mac = Input::get('mac');
		$unifi = new Unifi();
		$unifi->sendUnBlock($mac);
		return 1;
	}

	/* -----------------------  DHCP MANAGEMENT ----------------------*/

public function getWifiOnlineDevice(){

		$rowPerPage = Input::get('rowPerPage');
		$page 		= Input::get('page');
		$searchKey 	= Input::get('searchKey');
		$orderMode 	= Input::get('orderMode');
		
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		
		if(isset($rowPerPage) && isset($page) && isset($searchKey) && isset($orderMode))
		{
			
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
			       else if(isset($value['ip'])){
						if(stristr($value['ip'] , $find))
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
			if($searchKey != null)
				$result = search($searchKey,$result);

			// Our Call to Sort the Data
			usort($result, "fixem");
			
			$totalpage = (int)((count($result)/$rowPerPage)+0.99);

			$result = array_slice($result,($page-1)*$rowPerPage,$rowPerPage);

			return Response::json(array('code'=>200,'data'=>array('page'=>$page,'totalpage'=>$totalpage,'content'=>$result)));
		}
		else{
			return Response::json(array('code'=>302,'data'=>''));
		}
	}

	public function getWifiOfflineDevice(){
		$rowPerPage = Input::get('rowPerPage');
		$page 		= Input::get('page');
		$searchKey 	= Input::get('searchKey');
		$orderMode 	= Input::get('orderMode');
		
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept');
        header('Access-Control-Request-Method:POST, PUT, DELETE');
		if(isset($rowPerPage) && isset($page) && isset($searchKey) && isset($orderMode))
		{
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

			
			$regex = new MongoRegex('/'.$searchKey.'/i');
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
			$totalpage = (int)(($deviceCursor->count()/$rowPerPage)+0.99);
			$deviceCursor->skip(($page-1)*$rowPerPage);
			$deviceCursor->limit($rowPerPage);
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

			return Response::json(array('code'=>200,'data'=>array('page'=>$page,'totalpage'=>$totalpage,'content'=>$result)));
		}
		else{
			return Response::json(array('code'=>302,'data'=>''));
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