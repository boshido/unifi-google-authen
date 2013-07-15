<?php
//echo $_SERVER['REMOTE_ADDR'];

?>
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
			
			@media (max-width: 320px) 
			{
				//.form-container .circle {padding: 50%;}
			}

			@media (min-width: 321px) and (max-width: 800px)
			{
				//.form-container .circle {padding: 25%;}
			}

			@media (min-width: 801px)
			{
				//.form-container .circle {padding: 12.5%;}
			}
			
			html {
				background:gray;

			}
			.btn{
				//filter: blur(3px); 
				//-webkit-filter: blur(3px); 
				//-moz-filter: blur(3px);
				//-o-filter: blur(3px); -ms-filter: blur(3px);
			}
			.form-container {
				width:100%;
				height:auto;
				padding:100px 0px 10px 0px;
				background:#F47063;
				overflow:hidden;
				text-align:center;
			}
			.form-container .circle {
				display:block;
				//padding: 12.5%;
				margin:auto;
				width:150px;
				height:150px;
				border-radius:50%;
				-moz-border-radius:50%;
				-webkit-border-radius:50%;
				-khtml-border-radius: 50%;
				background:#FFFFFF;
				text-align:center;
				position: relative;
			}
			.form-container .circle span {
				position: absolute;
				width: 100%;
				left: 0;
				top: 48%;
				//line-height: 8em;
				//height: 8em;
				overflow: hidden;
			}

		</style>
		<link href="/css/pure-min.css" rel="stylesheet" media="screen"/>
		<link href="/css/grids-min.css" rel="stylesheet" media="screen">
		<link href="/css/overide.css" rel="stylesheet" media="screen">
	</head>
	<body>		
		<div class="menu">
			<div class="menu-content" >
				<div class="banner">fitm </div>
				<div class="help"><div class="menu-btn" >Help !</div></div>
			</div>	
		</div>

		<div class="form-container" autocomplete="off">
			<div class="circle">
			</div>
			<form action="/guest/google-redirect" method="get" style="margin-top:20px">
			  @if(isset($auth_url))
				<input class="btn" type="image" src="{{asset('/img/sign-in-with-fitm.png')}}" alt="Sign in with FITM 2.0"  style="width:246px;height:54px;"/>
				<input type="hidden" name="auth_url" value="{{$auth_url}}">
				<input type="hidden" name="auth_code" value="{{$auth_code}}">
			  @endif
			</form>	
		</div>
		
		<script>
		</script>
	</body>
</html>