<?php
class Ios{
	public static function sendPushNotification($deviceToken,$message){
		// Put your device token here (without spaces):
		//$deviceToken = '27216b1263b9fa530b2033e6f1c83d3d23e312347ae5d68fef5b630ade49484f';//Tae
		//$deviceToken = 'f374837f8a4c439d65c87593a5ca04b4d7af9a63770befbdf898a16c27fa7e4f';//Poon
		// Put your private key's passphrase here:
		$passphrase = 'Nothing14891';

		// Put your alert message here:
		//$message = 'จะแดกใหมข้าวอะ';

		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		echo 'Connected to APNS' . PHP_EOL;

		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'sound' => 'default'
			);

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		// Close the connection to the server
		fclose($fp);


		if (!$result)
			return 0;
		else
			return 1;
	}
}
?>