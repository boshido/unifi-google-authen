<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once 'unifi/Unifi.php';
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("FITM Wifi Authentication Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
//$client->setScopes('');
$client->setApprovalPrompt("auto");
$client->setAccessType('offline');

$oauth2 = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
	$client->authenticate($_GET['code']);
	$_SESSION['token'] = $client->getAccessToken();
	if(isset($_SESSION['id'])){
		$unifi = new Unifi();
		$unifi->sendAuthorization($_SESSION['id'], (12*60)); //authorizing user for 12 hours(12*60)
	}
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	return;
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
 /*if($client->isAccessTokenExpired()) {
	$client->authenticate();
	$newaccesstoken = json_decode($client->getAccessToken());
    $client->refreshToken($newaccesstoken->refresh_token);
 }*/
}

if ($client->getAccessToken()) {
	
  $user = $oauth2->userinfo->get();
  print_r($user);
  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
  $personMarkup = "$email<div><img src='$img?sz=50'></div>";

  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $auth_url = $client->createAuthUrl();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
<header><h1>Google UserInfo Sample App</h1></header>
<?php if(isset($personMarkup)): ?>
<?php print $personMarkup ?>
<?php endif ?>
<form action="/guest/google_redirect.php" method="POST">
<?php
  if(isset($auth_url)) {
	echo '<input type="image" src="/guest/img/sign-in-with-fitm.png" alt="Sign in with FITM 2.0"  style="width:246px;height:54px;"/>';
	echo '<input type="hidden" name="auth_url" value="'.$auth_url.'">';
	echo '<input type="hidden" name="auth_code" value="'.$_GET['auth_code'].'">';
  } else {
   print '<a class="logout" href="?logout">Logout</a>';
  }
?>
</body></html>