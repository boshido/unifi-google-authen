<?php
//Need Curl Enabled
class Unifi{
	private $data = array();

    function __construct()
    {
       	$this->data['unifiServer'] = "https://192.168.0.2:8443"; // Unifi Controller IP
		$this->data['unifiUser'] = "admin";
		$this->data['unifiPass'] = "admin";
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
	
	public function sendAuthorization($id, $minutes)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		
		// Send user to authorize and the time allowed
		$data = json_encode(array(
			'cmd'=>'authorize-guest',
			'mac'=>$id,
			'minutes'=>$minutes));

		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
		$value = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $value;
	}
	
	public function sendUnAuthorization($id)
	{
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		$data = json_encode(array(
			'cmd'=>'unauthorize-guest',
			'mac'=>$id));

		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
		$value = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $value;
	}
	
	public function getUser($array)
	{
		if(isset($array['mac']) || isset($array['ip']) || isset($array['all'])){
			$ch = curl_init();
			$ch = $this->sendLogin($ch);
			// Send user to authorize and the time allowed
			// Send the command to the API
			curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/stat/sta');
			$json = curl_exec ($ch);
			$json = json_decode($json);
			$value = false;
			if(isset($array['all'])){
				foreach($json->data as $key => $user){
					$value[] = $user;
				}
			}
			else if(isset($array['mac'])&& isset($array['ip'])){
				foreach($json->data as $key => $user){
					if(isset($user->mac) && isset($user->ip)){
						if($user->mac == $array['mac'] && $user->ip == $array['ip']){
							$value = $user;
							break;
						}
					}
				}
			}
			else if(isset($array['mac'])){
				foreach($json->data as $key => $user){
					if(isset($user->mac)){
						if($user->mac == $array['mac']){
							$value = $user;
							break;
						}
					}
				}
			}
			else if(isset($array['ip'])){
				foreach($json->data as $key => $user){
					if(isset($user->ip)){
						if($user->ip == $array['ip']){
							$value = $user;
							break;
						}
					}
				}
			}
			
			$ch = $this->sendLogout($ch);
			
			return $value;
		}
		else{
			return false;
		}
	}
	
	public function getHistory(){
		/*
		$ch = curl_init();
		$ch = $this->sendLogin($ch);
		
		// Send user to authorize and the time allowed
		$data = json_encode(array(
			'cmd'=>'authorize-guest',
			'mac'=>$id,
			'minutes'=>$minutes));

		// Send the command to the API
		curl_setopt($ch, CURLOPT_URL, $this->data['unifiServer'].'/api/cmd/stamgr');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
		$value = curl_exec ($ch);
		$ch = $this->sendLogout($ch);
		return $value;*/
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
	
}

?>