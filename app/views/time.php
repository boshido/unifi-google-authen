<?php
	date_default_timezone_set("Asia/Bangkok"); 
	if(isset($_GET['time'])){ 
		$time = $_GET['time'];
		echo date("Y-m-d H:i:s",time()-$time);
	}
	if(isset($_GET['time_start']) && isset($_GET['time_end'])){
		$time_start = $_GET['time_start'];
		$time_end = $_GET['time_end'];
		echo date("Y-m-d H:i:s",$time_start - $time_end);
	}	
	if(isset($_GET['time_show'])){
		$time_show = $_GET['time_show'];
		echo date("Y-m-d H:i:s",$time_show);
	}
?>
<html>
	<head>
		<title>Test Post</title>

	</head>
	
	<body>
		<h4>Check Time Now</h4>
		<form id="time" action="" method="GET">
			<input type="text" name="time" value="<?php if(isset($time)) echo $time; ?>" />
			<input type="submit" value="Submit" />
		</form>
		<h4>Check Time Between</h4>
		<form id="time" action="" method="GET">
			<input type="text" name="time_start" value="<?php if(isset($time_start)) echo $time_start; ?>" />
			<input type="text" name="time_end" value="<?php if(isset($time_end)) echo $time_end; ?>" />
			<input type="submit" value="Submit" />
		</form>
		<h4>Show Time</h4>
		<form id="time" action="" method="GET">
			<input type="text" name="time_show" value="<?php if(isset($time_show)) echo $time_show; ?>" />
			<input type="submit" value="Submit" />
		</form>
		<form id="send">
			<input type="text" name="username" value="admin" />
			<input type="text" name="password" value="admin" />
			<input type="text" name="login" value="Login" />

			<input type="submit" value="Submit" />
		</form>
		<div id="content"></div>
		<script type="text/javascript" src="/test/jquery-latest.js"></script>
		<script>
		var request;
		$('#send').submit(function(event){
				
				if(request) request.abort();
				
				var form = $(this);
				var inputs = form.find("input, select, button, textarea");
				var serializedData = form.serialize();
				inputs.prop('disabled',true);
				alert(serializedData);
				request = $.ajax({
					url: "https://192.168.0.5:8443/login",
					type: "post",
					data:serializedData
				});
				
				request.done(function (response, textStatus, jqXHR){
					// log a message to the console
					alert("Hooray, it worked!");
					$('#content').text(response);
					
				});
				
				request.fail(function (jqXHR, textStatus, errorThrown){
					// log the error to the console
					alert("The following error occured: "+textStatus, errorThrown);
				});

				// callback handler that will be called regardless
				// if the request failed or succeeded
				request.always(function () {
					// reenable the inputs
					inputs.prop("disabled", false);
				});
				// prevent default posting of form
				event.preventDefault();
		});
		</script>
	</body>
	
</html>