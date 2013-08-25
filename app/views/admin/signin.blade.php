<!DOCTYPE html>
<html>
	<head>
		<title>FITM 2.0 Wifi Authentication</title>
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
		<div class="overlay" style="display:none">
			<div id="loading" style="width:200px;height:200px;position:relative;margin:100px auto 10px auto" ></div>
			<h1 class="message" >Initializing your information.</h1>
		</div>
		<div class="login-box text-center">
			<img src="/img/fitm-en.png"  style="width:277.5px;height:145.5px;"></img>
			
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript"> </script>
		<script src="/js/heartcode-canvasloader-min.js" type="text/javascript"> </script>
		<script>
			
		</script>
	</body>
</html>