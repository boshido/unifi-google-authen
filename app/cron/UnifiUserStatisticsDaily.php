<?php
	error_reporting(E_ALL ^ E_NOTICE);
	set_include_path('/Users/boshido/Desktop/git/unifi-google-authen/app/libraries'); // Edit when change path
	require('Unifi.php');
	require('Database.php');
	$unifi = new Unifi();
	$onlineTmp = $unifi->getDevice(array('all'=>true));
	$db = Database::Connect();
	$userStatistic = $db->stat->hourly->user;
	$session = $db->session;

	// $stamp = strtotime("next hours",time());
	$stamp = time();
	$stamp = $stamp - ($stamp % 3600);
	$oldStatistic = $userStatistic->findOne(array("datetime"=>new MongoDate($stamp)));

	if($onlineTmp){ // When found users online
		echo "----------- Online Mode ---------\n";
		foreach($onlineTmp as $key => $value) $onlineDeviceMac[] = $value["mac"];
		$authorizedCursor = $unifi->getCurrentGuest($onlineDeviceMac,false);
		foreach ($authorizedCursor as $key => $value) $authorizedDevice[$value['mac']] = $value;
		
		if($oldStatistic){
			echo "Old Record\n";
			$bytes_sum = 0;
			$bytes_r_sum = 0;

			foreach ($oldStatistic['user'] as $key => $value) {
				$oldOnlineDevice[$value['mac']] = $value;
			}

			foreach($onlineTmp as $key => $value){
				if(isset($oldOnlineDevice[$value['mac']])){ // Old device
					if($value["assoc_time"] == $oldOnlineDevice[$value['mac']]["assoc_time"]){ // Old connection
						$tx_bytes = $oldOnlineDevice[$value['mac']]['tx_bytes'] + $value["tx_bytes"] - $oldOnlineDevice[$value['mac']]['tx_bytes_start'];
						$rx_bytes = $oldOnlineDevice[$value['mac']]['rx_bytes'] + $value["rx_bytes"] - $oldOnlineDevice[$value['mac']]['rx_bytes_start'];
						
						
						echo "Old connection\n";
					}
					else{ // New connection
						$find = array(
									'mac' => $value['mac'],
									'$and'=>array(
												array('assoc_time'=>array('$lte'=>$oldOnlineDevice[$value['mac']]["assoc_time"])),								
												array('disassoc_time'=>array('$gte'=>$oldOnlineDevice[$value['mac']]["assoc_time"]))
									)
								);

						$oldDeviceSession = $session->findOne($find);
						$tx_bytes = $oldOnlineDevice[$value['mac']]['tx_bytes'] + $value["tx_bytes"] + $oldDeviceSession["tx_bytes"] - $oldOnlineDevice[$value['mac']]['tx_bytes_start'];
						$rx_bytes = $oldOnlineDevice[$value['mac']]['rx_bytes'] + $value["rx_bytes"] + $oldDeviceSession["rx_bytes"] - $oldOnlineDevice[$value['mac']]['rx_bytes_start'];
						
						echo "New connection\n";
					}

					$bytes_r = ($value["bytes.r"]+$oldOnlineDevice[$value['mac']]['bytes.r'])/2;
					$ip = $value["ip"] != null ? $value["ip"] : $oldOnlineDevice[$value['mac']]['ip'];
					if(isset($authorizedDevice[$value['mac']]['google_id']) && $authorizedDevice[$value['mac']]['google_id'] != null){
						$is_auth	= true;
						$google_id 	= $authorizedDevice[$value['mac']]['google_id'];
						$email	 	= $authorizedDevice[$value['mac']]['email'];
						$name		= $authorizedDevice[$value['mac']]['name'];
					}
					else{
						$is_auth	= $oldOnlineDevice[$value['mac']]['is_auth'];
						$google_id	= $oldOnlineDevice[$value['mac']]['google_id'];
						$email 		= $oldOnlineDevice[$value['mac']]['email'];
						$name	 	= $oldOnlineDevice[$value['mac']]['name'];
					}
				}
				else{ // New device
					$tx_bytes = 0;
					$rx_bytes = 0;
					$bytes_r = $value["bytes.r"];
					$ip = $value["ip"];
					if(isset($authorizedDevice[$value['mac']]['google_id']) && $authorizedDevice[$value['mac']]['google_id'] != null){
						$is_auth	= true;
						$google_id 	= $authorizedDevice[$value['mac']]['google_id'];
						$email	 	= $authorizedDevice[$value['mac']]['email'];
						$name		= $authorizedDevice[$value['mac']]['name'];
					}
					else{
						$is_auth	= false;
						$google_id	= null;
						$email 		= null;
						$name	 	= null;
					}
				}

				$oldOnlineDevice[$value['mac']] = array(	"ip"				=>$ip,
															"mac"				=>$value["mac"],
															"hostname"			=>$value["hostname"],
															"tx_bytes_start"	=> new MongoInt64($value["tx_bytes"]),
															"rx_bytes_start"	=> new MongoInt64($value["rx_bytes"]),
															"tx_bytes"			=> new MongoInt64($tx_bytes),
															"rx_bytes"			=> new MongoInt64($rx_bytes),
															"bytes"				=> new MongoInt64($tx_bytes+$rx_bytes),
															"bytes.r"			=> new MongoInt64($bytes_r),
															"assoc_time"		=> new MongoInt64($value["assoc_time"]),
															"is_auth"			=> $is_auth,
															"google_id"			=> $google_id,
															"email"				=> $email,
															"name"				=> $name
														);

				$bytes_sum += $tx_bytes+$rx_bytes;
				$bytes_r_sum += $value["bytes.r"];
			}
			foreach ($oldOnlineDevice as $key => $value) {
				$onlineDevice[] = $value;
			}

			$bytes_r_sum += $oldStatistic['bytes.r'];
			$bytes_r_sum = $bytes_r_sum/2;
		}
		else{
			echo "New record\n";
			$bytes_sum = 0;
			$bytes_r_sum = 0;
			foreach($onlineTmp as $key => $value){
				$onlineDevice[] = array(	"ip"				=>$value["ip"],
											"mac"				=>$value["mac"],
											"hostname"			=>$value["hostname"],
											"tx_bytes_start"	=> new MongoInt64($value["tx_bytes"]),
											"rx_bytes_start"	=> new MongoInt64($value["rx_bytes"]),
											"tx_bytes"			=> new MongoInt64(0),
											"rx_bytes"			=> new MongoInt64(0),
											"bytes"				=> new MongoInt64(0),
											"bytes.r"			=> new MongoInt64($value["bytes.r"]),
											"assoc_time"		=> new MongoInt64($value["assoc_time"]),
											"is_auth"			=> isset($authorizedDevice[$value['mac']]['google_id']),
											"google_id"			=> isset($authorizedDevice[$value['mac']]['google_id']) ? $authorizedDevice[$value['mac']]['google_id'] : null,
											"email"				=> isset($authorizedDevice[$value['mac']]['google_id']) ? $authorizedDevice[$value['mac']]['email'] : null,
											"name"				=> isset($authorizedDevice[$value['mac']]['google_id']) ? $authorizedDevice[$value['mac']]['name'] : null
										);
				$bytes_sum += 0;
				$bytes_r_sum += $value["bytes.r"];
			}
		}
	}
	else{ // When not found any users 
		echo "----------- Offline Mode ---------\n";
		if($oldStatistic){
			$bytes_sum = $oldStatistic['bytes'];
			$bytes_r_sum = $oldStatistic['bytes.r'] / 2;
			foreach ($oldStatistic['user'] as $key => $value) {
				$value['bytes.r'] = $value['bytes.r'] / 2;
				$onlineDevice[] = $value;
			}
		}
		else{
			echo "New record\n";
			$bytes_sum = 0;
			$bytes_r_sum = 0;
			$onlineDevice = array();
		}
	}

	$insert = array(	"datetime"		=> new MongoDate($stamp),
						"bytes"			=> new MongoInt64($bytes_sum),
						"bytes.r"		=> new MongoInt64($bytes_r_sum),
						"user_count"	=> count($onlineDevice),
						"user"			=> $onlineDevice
					);
	$userStatistic->update(array("datetime"=>new MongoDate($stamp)),$insert,array('upsert'=>true));

	echo "Successful\n";
	echo $stamp."\n";
?>