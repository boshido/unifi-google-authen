<?php	

	$qest_code 	= $_GET['quest_code'];
	$mac 		= $_GET['mac'];
	$ap 		= $_GET['ap'];
	$name 		= $_GET['name'];
	$fname 		= $_GET['fname'];
	$lname 		= $_GET['lname'];
	$google_id 	= $_GET['google_id'];
	$email 		= $_GET['email'];
	$auth_type 	= $_GET['auth_type'];

	$url = '/guest/authorize?qest_code='.$quest_code.'&mac='.$mac.'&ap='.$ap.'&name='.$name.'&fname='.$fname.'&lname='.$lname.'&google_id='.$google_id.'&email='.$email.'&auth_type='.$auth_type;
?>

<a href='<?php echo $url ?>'> Authorize</a>