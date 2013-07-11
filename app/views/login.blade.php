<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
<header><h1>Google UserInfo Sample App</h1></header>
<?php if(isset($personMarkup)): ?>
<?php print $personMarkup ?>
<?php endif ?>
<form action="/guest/google-redirect" method="get">
<?php
  if(isset($auth_url)) {
	echo '<input type="image" src="'.asset('/img/sign-in-with-fitm.png').'" alt="Sign in with FITM 2.0"  style="width:246px;height:54px;"/>';
	echo '<input type="hidden" name="auth_url"+ value="'.$auth_url.'">';
	echo '<input type="hidden" name="auth_code" value="'.$auth_code.'">';
  } else {
   print '<a class="logout" href="?logout">Logout</a>';
  }
?>
</body></html>