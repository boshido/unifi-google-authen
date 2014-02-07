<?php
	require('../libraries/Unifi.php');
	require('../libraries/Database.php');
	$unifi = new Unifi();
	$onlineTmp = $unifi->getDevice(array('all'=>true));
	$db = Database::Connect();
	$userStatistic = $db->stat->hourly->user;
	$session = $db->session;

	if($onlineTmp){
		$stamp = strtotime("next hours",time());
		$stamp = $stamp - ($stamp % 3600);
		$oldStatistic = $userStatistic->findOne(array("datetime"=>new MongoDate($stamp)));

		foreach($onlineTmp as $key => $value) $onlineDeviceMac[] = $value["mac"];
		$authorizedCursor = $unifi->getCurrentGuest($onlineDeviceMac,false);
		foreach ($authorizedCursor as $key => $value) $authorizedDevice[$value['mac']] = $value;
		
		if($oldStatistic){
			echo "Old Record\n";
			$bytes = 0;
			$bytes_r = 0;

			foreach ($oldStatistic['user'] as $key => $value) {
				$oldOnlineDevice[$value['mac']] = $value;
			}

			foreach($onlineTmp as $key => $value){

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
				
				$oldOnlineDevice[$value['mac']] = array(	"ip"				=>$value["ip"] != null ? $value["ip"] : $oldOnlineDevice[$value['mac']]['ip'] ,
												"mac"				=>$value["mac"],
												"google_id"			=>$authorizedDevice[$value['mac']]['google_id'] != null ? $authorizedDevice[$value['mac']]['google_id'] : $oldOnlineDevice[$value['mac']]['google_id'] ,
												"email"				=>$authorizedDevice[$value['mac']]['google_id'] != null ?  $authorizedDevice[$value['mac']]['email'] : $oldOnlineDevice[$value['mac']]['email'],
												"name"				=>$authorizedDevice[$value['mac']]['google_id'] != null ? $authorizedDevice[$value['mac']]['name'] : $oldOnlineDevice[$value['mac']]['name'],
												"tx_bytes_start"	=> new MongoInt64($value["tx_bytes"]),
												"rx_bytes_start"	=> new MongoInt64($value["rx_bytes"]),
												"tx_bytes"			=> new MongoInt64($tx_bytes),
												"rx_bytes"			=> new MongoInt64($rx_bytes),
												"bytes"				=> new MongoInt64($tx_bytes+$rx_bytes),
												"bytes.r"			=> new MongoInt64(($value["bytes.r"]+$oldOnlineDevice[$value['mac']]['bytes.r'])/2),
												"assoc_time"		=>new MongoInt64($value["assoc_time"])
											);
				$bytes += $tx_bytes+$rx_bytes;
				$bytes_r += $value["bytes.r"];
			}
			foreach ($oldOnlineDevice as $key => $value) {
				$onlineDevice[] = $value;
			}
			echo $bytes_r."\n";
			$bytes_r += $oldStatistic['bytes.r'];
			$bytes_r = $bytes_r/2;
		}
		else{
			echo "New record\n";
			$bytes = 0;
			$bytes_r = 0;
			foreach($onlineTmp as $key => $value){
				$onlineDevice[] = array(	"ip"				=>$value["ip"],
											"mac"				=>$value["mac"],
											"google_id"			=>$authorizedDevice[$value['mac']]['google_id'],
											"email"				=>$authorizedDevice[$value['mac']]['email'],
											"name"				=>$authorizedDevice[$value['mac']]['name'],
											"tx_bytes_start"	=> new MongoInt64($value["tx_bytes"]),
											"rx_bytes_start"	=> new MongoInt64($value["rx_bytes"]),
											"tx_bytes"			=> new MongoInt64(0),
											"rx_bytes"			=> new MongoInt64(0),
											"bytes"				=> new MongoInt64(0),
											"bytes.r"			=> new MongoInt64($value["bytes.r"]),
											"assoc_time"		=>new MongoInt64($value["assoc_time"])
										);
				$bytes += 0;
				$bytes_r += $value["bytes.r"];
			}
		}
		$insert = array(	"datetime"		=> new MongoDate($stamp),
							"bytes"			=> new MongoInt64($bytes),
							"bytes.r"		=> new MongoInt64($bytes_r),
							"user"			=>$onlineDevice
						);
		$userStatistic->update(array("datetime"=>new MongoDate($stamp)),$insert,array('upsert'=>true));

		
		var_dump($stamp);
	}
?>