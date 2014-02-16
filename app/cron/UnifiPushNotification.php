<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Asia/Bangkok");
set_include_path('/Users/boshido/Desktop/git/unifi-google-authen/app/libraries');// Edit when change path
require('Ios.php');
require('Unifi.php');
require('Database.php');

$unifi = new Unifi();
$db = Database::Connect();
$alarm 	= $db->alarm;
$ios 	= $db->token->ios;
$ap  	= $db->device;

$time = time();
$alarmCursor = $alarm->findOne( 
						array('$and' => 
							array(
								array('datetime' => array('$gte' => new MongoDate($time-(60*5)))),
								array('datetime' => array('$lte' => new MongoDate($time) )) ,
								array('send'=>array('$ne'=>true))
							)
						)
					);
if($alarmCursor){
	if($alarmCursor['key']=="EVT_AP_Lost_Contact"){
		$apCursor = $ap->findOne(array('mac'=>$alarmCursor['ap']),array('name'=>1,'mac'=>1));
		if($apCursor){
			if(isset($apCursor['name']))
				$apName = $apCursor['name'];
			else 
				$apName = $apCursor['mac'];

			$time = date("Y-m-d H:i:s",$alarmCursor['datetime']->sec);
			$time = substr_replace($time, substr($time,0,4)+543, 0,4);
			
			$iosCursor = $ios->find(array('enabled'=>true));
			foreach ($iosCursor as $key => $value) {
				echo "Run\n";
				$result = Ios::sendPushNotification($value['token_id'],'['.$time.'] AP '.$apName.' was disconnected');
				if($result){
					$alarm->update(array('_id'=>$alarmCursor['_id']),array('$set'=>array('send'=>true)));
					echo "Update Success\n";
				}
			}
		}
	}
}
else{
	echo "Nothing Happend\n";
}


