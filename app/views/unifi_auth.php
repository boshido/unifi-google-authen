<?php
require_once 'unifi/Unifi.php';
session_start();

if ($_SESSION['loggingin'] == "unique key") // Check to see if the form has been posted to
{
	$unifi = new Unifi();
    $unifi->sendAuthorization($_SESSION['id'], (12*60)); //authorizing user for 12 hours(12*60)
	unset($_SESSION['loggingin']);
	unset($_SESSION['authcode']);
	header('Location:http://www.Google.com');
}

?>
