<?php
require_once 'unifi/Unifi.php';
session_start();
$auth_code = $_POST['auth_code'];
$auth_url = $_POST['auth_url'];



if($_SESSION['auth_code'] == $auth_code){
	if(isset($_SESSION['id'])){
		$unifi = new Unifi();
		$unifi->sendAuthorization($_SESSION['id'], 1); // authorizing 1 minutes for going through google authentication
		unset($_SESSION['authcode']);
	}
}
?>
<script>
	window.location.href = "<?php echo $auth_url;?>";
</script>
