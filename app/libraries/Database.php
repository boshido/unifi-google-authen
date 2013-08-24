<?php
class Database {
		public static function Connect(){
			$username = "admin"; //Database Username
			$password = "admin"; //Database Password
			$database = "ace"; //Database Name
			$ip = "127.0.0.1:27117";//Database Ip and Port
			try 
			{
				$m = new Mongo($ip); // Connecting to database on localhost
				$db = $m->selectDB($database);
				
				// Request authentication from database
				$salted = "${username}:mongo:${password}";
				$hash = md5($salted);
				$nonce = $db->command(array("getnonce" => 1));
				$saltedHash = md5($nonce["nonce"]."${username}${hash}");
				$result = $db->command(array ("authenticate" => 1,
					"user" => $username,
					"nonce" => $nonce["nonce"],
					"key" => $saltedHash
				));
				return $db;
			}
			catch (MongoConnectionException $e ) 
			{
				echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
				exit();
			} 
		}
}
?>