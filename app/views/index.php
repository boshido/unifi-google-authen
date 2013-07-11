<?php
session_start();
if(isset($_GET['id'])){
	$chk = md5(uniqid(rand(), true));
	$_SESSION['id'] = $_GET['id'];         		//user's mac address
	$_SESSION['ap'] = $_GET['ap'];          	//AP mac
	$_SESSION['ssid'] = $_GET['ssid'];      	//ssid the user is on (POST 2.3.2)
	$_SESSION['time'] = $_GET['t'];         	//time the user attempted a request of the portal
	$_SESSION['ref_url'] = $_GET['url'];     	//url the user attempted to reach
	$_SESSION['auth_code'] = $chk; 				//key to use to check if the user used this form or not
												// -- prevents them from simply going to /authorized.php on their own
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] .'/guest/google_auth.php?auth_code='.$chk;
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}
/*
<script>
	window.open("<?php echo $redirect; ?>", "mywindow","status=1,toolbar=1");
</script>	
*/									
?>

