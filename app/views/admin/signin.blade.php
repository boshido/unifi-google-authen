<!DOCTYPE html>
<html>
	<head>
		<title>FITM 2.0 Wi-Fi Administrator</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<script>
			if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			  var msViewportStyle = document.createElement("style");
			  msViewportStyle.appendChild(
				document.createTextNode(
				  "@-ms-viewport{width:auto!important}"
				)
			  );
			  document.getElementsByTagName("head")[0].
				appendChild(msViewportStyle);
			}
		</script>
		<style>
			html {
				background: url(/img/bg.png) repeat center center fixed; 
			}
			.btn{
				//filter: blur(3px); 
				//-webkit-filter: blur(3px); 
				//-moz-filter: blur(3px);
				//-o-filter: blur(3px); -ms-filter: blur(3px);
			}
			.checkbox{
				color:white;
				font-size:17px;
			}
		</style>
		<link rel="shortcut icon" href="/img/ico.png" type="image/x-icon" />
		<link href="/css/pure-min.css" rel="stylesheet" media="screen">
		<link href="/css/grids-min.css" rel="stylesheet" media="screen">
		<link href="/css/admin.css" rel="stylesheet" media="screen">
	</head>
	<body>		
		<div class="login-box text-center">
			<div>
				<img src="/img/fitm-en.png"  style="width:277.5px;height:145.5px;"></img>
				<h2 class="header">Wi-Fi Administrator</h2>
			</div>
			<div >
				<form class="pure-form pure-form-aligned" method="post" action="">
					<fieldset>
						<div class="pure-control-group">
							<label for="username" style="width:70px">Username</label>
							<input name="username" type="text" placeholder="Username">
						</div>
						<div class="pure-control-group">
							<label for="password" style="width:70px">Password</label>
							<input name="password" type="password" placeholder="Password">
						</div>
						<div class="pure-control-group" style="margin-top:20px;">
							<button type="submit" class="pure-button pure-button-primary" style="font-family:'Oswald';">Sign In</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript"> </script>
		<script src="/js/heartcode-canvasloader-min.js" type="text/javascript"> </script>
		<script  type="text/javascript">
			$('input[name="username"]').focus();
		</script>
	</body>
</html>