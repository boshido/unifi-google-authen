<?php
//Need Curl enabled and add extension=php_mongo.dll to your php.ini
class Unifi{
	private $data = array();

    function __construct()
    {
       	$this->data['unifiServer'] = "https://127.0.0.1:8443"; // Unifi Controller IP
		$this->data['unifiUser'] = "admin";
		$this->data['unifiPass'] = "intlab";
    }
	
    public function __get($member) {
        if (isset($this->data[$member])) {
            return $this->data[$member];
        }
    }
	
	public function __set($member, $value) {
        // The ID of the dataset is read-only
        if ($member == "id") {
            return;
        }
        if (isset($this->data[$member])) {
            $this->data[$member] = $value;
        }
    }
	
	public function sendAuthorization($id, $minutes , $ap_mac=null)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		
		// Send user to authorize and the time allowed
		$data = array(
			'cmd'=>'authorize-guest',
			'mac'=>$id,
			'minutes'=>$minutes);
		if($ap_mac != null) $data['ap_mac']=$ap_mac;
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}
	
	public function sendUnAuthorization($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = array(
			'cmd'=>'unauthorize-guest',
			'mac'=>$id);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}	
	
	public function sendBlock($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = array(
			'cmd'=>'block-sta',
			'mac'=>$id);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}
	
	public function sendUnBlock($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = array(
			'cmd'=>'unblock-sta',
			'mac'=>$id);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}
	
	public function sendReconnect($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = array(
			'cmd'=>'kick-sta',
			'mac'=>$id);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}
	
	public function sendRestartAp($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = array(
			'cmd'=>'restart',
			'mac'=>$id);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/devmgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.json_encode($data));
		$result = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $result;
	}
	
	
	public function getDevice($array)
	{
		if(isset($array['mac']) || isset($array['ip']) || isset($array['all'])){
			$ch = curl_init();
			$ch = $this->sendLogin($ch);
			// Send user to authorize and the time allowed
			// Send the command to the API
			curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/stat/sta');
			$json = curl_exec ($ch);
			$json = json_decode($json);
			$result = false;
			if(isset($array['all'])){
				foreach($json->data as $key => $user){
					$result[] = $user;
				}
			}
			else if(isset($array['mac'])&& isset($array['ip'])){
				foreach($json->data as $key => $user){
					if(isset($user->mac) && isset($user->ip)){
						if($user->mac == $array['mac'] && $user->ip == $array['ip']){
							$result = $user;
							break;
						}
					}
				}
			}
			else if(isset($array['mac'])){
				foreach($json->data as $key => $user){
					if(isset($user->mac)){
						if($user->mac == $array['mac']){
							$result = $user;
							break;
						}
					}
				}
			}
			else if(isset($array['ip'])){
				foreach($json->data as $key => $user){
					if(isset($user->ip)){
						if($user->ip == $array['ip']){
							$result = $user;
							break;
						}
					}
				}
			}
			
			$ch = $this->sendLogout($ch);
			
			return $result;
		}
		else{
			return false;
		}
	}
	
	public function getAp($mac=null)
	{
		
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/stat/device');
		$json = curl_exec ($ch);
		
		$result = false;
		$json = json_decode($json,1);
		if($json != null){
			if($mac==null){
				$result = $json['data'];
			}
			else{
				foreach($json['data'] as $key => $device){
					if($device['mac'] == $mac ){
						$result = $device;
						break;
					}
				}
			}
		}
		$ch = $this->sendLogout($ch);
		
		return $result;
	}
	
	public function sendLogin($ch){
		// Disable Curl print result
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// We are posting data
		curl_setopt($ch, CURLOPT_POST, TRUE);
		// Set up cookies
		$cookie_file = "/tmp/unifi_cookie";
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		// Allow Self Signed Certs
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// Force SSL3 only
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		// Login to the UniFi controller
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer']."/login");
		curl_setopt($ch, CURLOPT_POSTFIELDS,
			'login=login&username='.$this->data['unifiUser'].'&password='.$this->data['unifiPass']);
		// send login command
		curl_exec ($ch);
		return $ch;
	}
	
	public function sendLogout($ch){
		// Logout of the UniFi Controller
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/logout');
		curl_exec ($ch);
		curl_close ($ch);
	}
	
	/*
	public function getCurrentsGuest($mac)
	{
		
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = json_encode(array("within"=>"24"));
		
		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/stat/guest');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
		$json = curl_exec ($ch);
		
		$value = false;
		$json = json_decode($json);
		$time = time();
		if($json != null){
			foreach($json->data as $key => $user){
				if($user->mac == $mac && $user->start <= $time && $time <= $user->end){
					$value = $user;
					break;
				}
			}
		}
		$ch = $this->sendLogout($ch);
		
		return $value;
	}*/
	
	public function getMapList($file_id=null)
	{		
		$db = Database::Connect();
		$map = $db->map;
		$result = array();
		
		$find = array();
		if($file_id != null)$find['file_id'] = $file_id;
		
		$cursor = $map->find($find);
		$cursor->sort(array('order'=>1));
		
		foreach($cursor as $key => $value){
			$value['_id']=(string)$value['_id'];
			$result[] = $value;
		}
		
		if($result == null)$result=false;
		
		return $result;
	}
	
	public function getMap($file_id=null)
	{
		$db = Database::Connect();
		$chunks = new MongoCollection($db,'map.chunks');
		$result = '';
		
		$find = array();
		if($file_id != null){
			$find['files_id'] = new MongoId($file_id);
			$cursor = $chunks->find($find);
			foreach($cursor as $key => $value){
				$result = $result.$value['data']->bin;
			}
		}
		if($result == '')$result=false;
		return $result;
		
	}
	
	public function getCurrentGuest($mac=null,$findOne=true)
	{		
		$db = Database::Connect();
		$guest = $db->guest;
		$result = array();
		$time = time();
		
		$find = array('$and'=>array(array('start'=>array('$lte'=>$time)),array('end'=>array('$gte'=>$time))));
		if($mac != null){
			if(is_array($mac)){
				$find['mac'] = array('$in' => $mac);
			}
			else {
				$find['mac'] = $mac;
			}
		}
		if($findOne)$result = $guest->findOne($find);
		else 
		{
			$cursor = $guest->find($find);
			foreach($cursor as $key => $value){
				$value['_id']=(string)$value['_id'];
				$result[] = $value;
			}
		}
		
		if($result == null)$result=false;
		
		return $result;
	}
	
	public function setCurrentGuest($mac,$data){
	
		$db = Database::Connect();
		$guest = $db->guest;
		$time = time();
		
		$find = array('mac' => $mac,'$and'=>array(array('start'=>array('$lte'=>$time)),array('end'=>array('$gte'=>$time))));
		$set = array('$set'=>$data);
		$cursor = $guest->update($find,$set);

	}
	
	public function getStat($mac,$limit,$sort,$sort_type=1)
	{
		$db = Database::Connect();
		$session = $db->session;
		$result = array();
		
		$find = array('mac' => $mac);
		if($limit != null) $cursor = $session->find($find)->limit($limit);
		else $cursor = $session->find($find);
		
		if($sort != null){
			$cursor->sort(array($sort=>$sort_type));
		}
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$value['user_id'] = (string)$value['user_id'];
			$result[] =$value;
		}
		
		return $result;
	}
	
	public function getStatDaily($mac, $at , $to)
	{	
		$at = $at != null ? strtotime("midnight", $at) : strtotime("midnight", time());
		$to = $to != null ? strtotime("tomorrow", $to)- 1 : strtotime("tomorrow", time())- 1; 
	
		$db = Database::Connect();
		$session = $db->session;
		$result = array();
		
		$find = array('mac' => $mac,'$and'=>array(array('assoc_time'=>array('$gte'=>$at)),array('assoc_time'=>array('$lte'=>$to))));
		$cursor = $session->find($find);
		$cursor->sort(array('assoc_time'=>-1));
		
		$tmp = array();
		$stamp = strtotime("midnight", $at); 
		do{
			$tmp[$stamp]['date']=$stamp;
			$tmp[$stamp]['tx_bytes']=0;
			$tmp[$stamp]['rx_bytes']=0;
			$stamp = strtotime("tomorrow", $stamp);
		}while($stamp <= strtotime("midnight",$to));
		
		foreach($cursor as $key => $value){
			$time = strtotime("midnight", $value['assoc_time']);
			$tmp[$time]['tx_bytes'] = $tmp[$time]['tx_bytes']+$value['tx_bytes'];
			$tmp[$time]['rx_bytes'] = $tmp[$time]['rx_bytes']+$value['rx_bytes'];
		}
		
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		
		return $result;
	}
	
	public function getStatSummary($type, $data)
	{	
		$db = Database::Connect();
		$stat = $db->stat;
		$result = array();
		
		$find = array($type => $data);
		$result = $stat->findOne($find);
		$result['_id']=(string)$result['_id'];
		
		return $result;
	}
	public function getTrafficReport($time,$type)
	{	

		$db = Database::Connect();
		if($type=='hourly'){
			$stat = $db->stat->hourly->system;
			$result = array();
			$cursor = $stat->find( 
				array('$and' => 
					array(
						array('datetime' => array('$gte' => new MongoDate(strtotime('-7 hours',$time)))),
						array('datetime' => array('$lte' => new MongoDate($time) )) 
					)
				)
			);
		}
		else{
			$stat = $db->stat->daily->system;
			$result = array();
			$cursor = $stat->find( 
				array('$and' => 
					array(
						array('datetime' => array('$gte' => new MongoDate(strtotime('-7 day',$time)))),
						array('datetime' => array('$lte' => new MongoDate($time) )) 
					)
				)
			);
		}

		$cursor->sort(array('datetime'=>-1))->limit(10);
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$result[] =$value;
		}

		return $result;
	}
}

?>