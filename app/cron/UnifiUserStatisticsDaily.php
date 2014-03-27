<?php
	error_reporting(E_ALL ^ E_NOTICE);
	//set_include_path('E:/web/htdocs/app/libraries');// Edit when change path
	set_include_path('/Users/boshido/Desktop/git/unifi-google-authen/app/libraries');// Edit when change path
	require('Unifi.php');
	require('Database.php');
	$unifi = new Unifi();
	$onlineTmp = $unifi->getDevice(array('all'=>true));
	$db = Database::Connect();
	$userStatistic = $db->stat->daily->user;
	$session = $db->session;

	// $stamp = strtotime("next hours",time());
	$stamp = time();
	$stamp = $stamp - ($stamp % 86400);
	$bytes_sum = 0;
	$bytes_r_sum = 0;
	$saveToDB = array();

	$oldStatistic = $userStatistic->findOne(array("datetime"=>new MongoDate($stamp)));
	$authorizedCursor = $unifi->getCurrentGuest($onlineDeviceMac,false);
	$authorizedDevice = array();
	if($authorizedCursor)
		foreach ($authorizedCursor as $key => $value) {
			if(isset($value['google_id']))
				$authorizedDevice[$value['mac']] = $value;
		}

	if($onlineTmp){ // When found users online
		echo "----------- Found Users ---------\n";

		foreach($onlineTmp as $key => $value) {
			$onlineDeviceMac[] = $value["mac"];
			if( isset($authorizedDevice[$value['mac']]) ){
				$value['is_auth']	= true;
				$value['google_id'] = $authorizedDevice[$value['mac']]['google_id'];
				$value['email']	 	= $authorizedDevice[$value['mac']]['email'];
				$value['name']		= $authorizedDevice[$value['mac']]['name'];
			}
			else{
				$value['is_auth']	= false;
				$value['google_id']	= null;
				$value['email'] 	= null;
				$value['name']	 	= null;
			}
			$value["tx_bytes_start"] = $value["tx_bytes"];
			$value["rx_bytes_start"] = $value["rx_bytes"];
			$value['bytes'] = 0;
			$value['tx_bytes'] = 0;
			$value['rx_bytes'] = 0;
			$value['new'] = true;
			$onlineTmp[$key]   = $value;
		}
		
	//----------------------------------------------------------------------------------------------------------------------------------------//
		for ($i=0; $i <count($onlineTmp) ; $i++) { 
			echo 'NEW '. $onlineTmp[$i]['mac']."\n";
		}
		if($oldStatistic){ // Run with old record
			echo "Old Record\n";
			$dbDeviceList = array();
			foreach ($oldStatistic['user'] as $key => $value) {
				if( isset($authorizedDevice[$value['mac']]) ){
					$value['is_auth']	= true;
					$value['google_id'] = $authorizedDevice[$value['mac']]['google_id'];
					$value['email']	 	= $authorizedDevice[$value['mac']]['email'];
					$value['name']		= $authorizedDevice[$value['mac']]['name'];
				}
				$dbDeviceList[$value['mac']] = $value;
			}
			
			foreach ($dbDeviceList as $key => $value) { // Old Device
				$offlineFlag = false;
				for ($i=0; $i <count($onlineTmp) ; $i++) { 

					echo $value['mac'].' OLD -- NEW '. $onlineTmp[$i]['mac']."\n";
					if($value['mac'] == $onlineTmp[$i]['mac']){
						echo "Online\n"; 
						//Online
						if($value["assoc_time"] == $onlineTmp[$i]["assoc_time"]){ // Old Session
							if($onlineTmp[$i]["tx_bytes_start"] > $value["tx_bytes_start"]){ // Old Session
								echo "Old Session\n";
								$value['tx_bytes'] += $onlineTmp[$i]['tx_bytes_start'] - $value['tx_bytes_start'];
								$value['rx_bytes'] += $onlineTmp[$i]['rx_bytes_start'] - $value['rx_bytes_start'];
								
							}
						}
						

						// if($value["assoc_time"] == $onlineTmp[$i]["assoc_time"]){ // Old Session
						// 	echo "Old Session\n";
						// 	$value['tx_bytes'] += $onlineTmp[$i]['tx_bytes_start'] - $value['tx_bytes_start'];
						// 	$value['rx_bytes'] += $onlineTmp[$i]['rx_bytes_start'] - $value['rx_bytes_start'];
							
						// }
						// else{ // New Session
						// 	echo "New Session\n";
						// 	$find = array(
						// 				'mac' => $value['mac'],
						// 				'assoc_time'=>$value["assoc_time"]
						// 				// '$and'=>array(
						// 				// 			array('assoc_time'=>array('$lte'=>$oldOnlineDevice[$value['mac']]["assoc_time"])),								
						// 				// 			array('disassoc_time'=>array('$gte'=>$oldOnlineDevice[$value['mac']]["assoc_time"]))
						// 				// )
						// 			);

						// 	$oldDeviceSession = $session->findOne($find);
						// 	if($oldDeviceSession){
						// 		$value['tx_bytes'] += $oldDeviceSession['tx_bytes'] - $value['tx_bytes_start'] + $onlineTmp[$i]['tx_bytes_start'];
						// 		$value['rx_bytes'] += $oldDeviceSession['rx_bytes'] - $value['rx_bytes_start'] + $onlineTmp[$i]['rx_bytes_start'];
						// 		echo "Found Session\n"; 
						// 	}
						// 	else{
						// 		if($value['offline']){
						// 			$value['tx_bytes'] += $onlineTmp[$i]['tx_bytes_start'];
						// 		 	$value['rx_bytes'] += $onlineTmp[$i]['rx_bytes_start'];
						// 		 	echo "Not Found Session Already Use\n";
						// 		}
						// 		else
						// 			echo "Not Found Session Not Real Offline\n"; 
						// 	}
						// }

						if(isset($onlineTmp[$i]["ip"]))	$value['ip'] = $onlineTmp[$i]["ip"];
						$value['assoc_time'] = $onlineTmp[$i]['assoc_time'];
						$value['bytes.r']  += $onlineTmp[$i]['bytes.r'];
						$value['bytes'] = $value['tx_bytes']  + $value['rx_bytes'];
						$value['tx_bytes_start'] = $onlineTmp[$i]['tx_bytes_start'];
						$value['rx_bytes_start'] = $onlineTmp[$i]['rx_bytes_start'];
						//$value['offline'] = false;
						$onlineTmp[$i]['new'] = false;
						
						$offlineFlag = false;
						break;
					}
					else{
						$offlineFlag = true;
					}
				}

				if($offlineFlag){
				    //Offline
					echo "Offline\n";
					// $find = array(
					// 			'mac' => $value['mac'],
					// 			'assoc_time'=>$value["assoc_time"]
					// 			// '$and'=>array(
					// 			// 			array('assoc_time'=>array('$lte'=>$oldOnlineDevice[$i]["assoc_time"])),								
					// 			// 			array('disassoc_time'=>array('$gte'=>$oldOnlineDevice[$i]["assoc_time"]))
					// 			// )
					// 		);

					// $oldDeviceSession = $session->findOne($find);
					// if($oldDeviceSession){
					// 	$value['tx_bytes'] += $oldDeviceSession['tx_bytes'] - $value['tx_bytes_start'];
					// 	$value['rx_bytes'] += $oldDeviceSession['rx_bytes'] - $value['rx_bytes_start'];
					// 	$value['offline'] = true;
					// 	echo "Found Session\n"; 
					// }
					// else{
					// 	$value['offline'] = false;
					// 	echo "Not Found Session\n"; 
					// }

					// $value['assoc_time'] = -1;
					// $value['bytes'] = $value['tx_bytes']  + $value['rx_bytes'];
					// $value['tx_bytes_start'] = -1;
					// $value['rx_bytes_start'] = -1;
				}

				$saveToDB[]=$value;
			}

			foreach ($onlineTmp as $key => $value) { // New Device
				if($value['new']){
					echo "New device \n";
					echo $value['mac']."\n";
					//$value['offline'] = false;
					$value['bytes.r'] = $value['bytes.r']*2;
					$saveToDB[]=$value;
				}
			}

		}
		else{ // Save with new record
			echo "New record\n";
			foreach ($onlineTmp as $key => $value) { // New Device
				echo "New device \n";
				//$value['offline'] = false;
				$value['bytes.r'] = $value['bytes.r']*2;
				$saveToDB[]=$value;
			}
			//if($bytes_r_sum>0)$bytes_r_sum = $bytes_r_sum/count($saveToDB);
		}
	}
	else{ // When not found any users 
		echo "----------- Not Found Users ---------\n";
		if($oldStatistic){
			echo "Old record\n";
			echo "Offline\n";
			// foreach ($oldStatistic['user'] as $key => $value) {
			// 	$find = array(
			// 				'mac' => $value['mac'],
			// 				'assoc_time'=>$value["assoc_time"]
			// 				// '$and'=>array(
			// 				// 			array('assoc_time'=>array('$lte'=>$oldOnlineDevice[$value['mac']]["assoc_time"])),								
			// 				// 			array('disassoc_time'=>array('$gte'=>$oldOnlineDevice[$value['mac']]["assoc_time"]))
			// 				// )
			// 			);

			// 	$oldDeviceSession = $session->findOne($find);
			// 	if($oldDeviceSession){
			// 		$value['tx_bytes'] += $oldDeviceSession['tx_bytes'] - $value['tx_bytes_start'];
			// 		$value['rx_bytes'] += $oldDeviceSession['rx_bytes'] - $value['rx_bytes_start'];
			// 		$value['offline'] = true;
			// 		echo "Found Session\n"; 
			// 	}
			// 	else{
			// 		$value['offline'] = false;
			// 		echo "Not Found Session\n"; 
			// 	}
			// 	$value['assoc_time'] = -1;
			// 	$value['bytes'] = $value['tx_bytes']  + $value['rx_bytes'];
			// 	$value['tx_bytes_start'] = -1;
			// 	$value['rx_bytes_start'] = -1;

			$saveToDB=$oldStatistic['user'];

		}
		else{
			echo "New record\n";


		}
	}

	foreach ($saveToDB as $key => $value) {
		$bytes_sum += $value["bytes"];
		if($value['bytes.r'] >0) $value['bytes.r'] = floor($value['bytes.r'] / 2);
		$bytes_r_sum += $value["bytes.r"];

		$saveToDB[$key] = array(	"ip"				=>$value["ip"],
										"mac"				=>$value["mac"],
										"hostname"			=>$value["hostname"],
										"tx_bytes_start"	=> new MongoInt64($value["tx_bytes_start"]),
										"rx_bytes_start"	=> new MongoInt64($value["rx_bytes_start"]),
										"tx_bytes"			=> new MongoInt64($value["tx_bytes"]),
										"rx_bytes"			=> new MongoInt64($value["rx_bytes"]),
										"bytes"				=> new MongoInt64($value["bytes"]),
										"bytes.r"			=> new MongoInt64($value["bytes.r"]),
										"assoc_time"		=> new MongoInt64($value["assoc_time"]),
										"is_auth"			=> $value["is_auth"],
										"google_id"			=> $value["google_id"],
										"email"				=> $value["email"],
										"name"				=> $value["name"],
										"offline"			=> $value["offline"]
									);
	}
	//if($bytes_r_sum>0)$bytes_r_sum = $bytes_r_sum/count($saveToDB);

	$insert = array(	"datetime"		=> new MongoDate($stamp),
						"bytes"			=> new MongoInt64($bytes_sum),
						"bytes.r"		=> new MongoInt64($bytes_r_sum),
						"user_count"	=> count($saveToDB),
						"user"			=> $saveToDB
					);
	$userStatistic->update(array("datetime"=>new MongoDate($stamp)),$insert,array('upsert'=>true));

	echo "Successful\n";
	echo $stamp."\n";
?>