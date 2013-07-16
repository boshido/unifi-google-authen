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
				background: url(/img/bg{{rand(1,4)}}.jpg) no-repeat center center fixed; 
				  -webkit-background-size: cover;
				  -moz-background-size: cover;
				  -o-background-size: cover;
				  background-size: cover;
				  filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg3.jpg', sizingMethod='scale');
				  -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg3.jpg', sizingMethod='scale')";
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
		<div class="overlay" style="display:none">
			<div id="loading" style="width:200px;height:200px;position:relative;margin:100px auto 10px auto" ></div>
			<h1 class="message" >Initializing your information.</h1>
		</div>
		<div class="menu">
			<div  >
				<div class="banner">fitm </div>
				<div class="help"><div class="menu-btn" >Help !</div></div>
			</div>	
		</div>
		<div class="shadow-fade">
		</div>
		<div class="bar login center">
			<div><h1 style="color:rgb(255, 255, 255);background-color:rgba(215, 72, 44,0.6);font-family:'Oswald',arial,sans-serif;">Wi-Fi Authentication</h1></div>
			<img src="/img/photo.jpg" class="img-circle profile-img" ></img>
			<form action="/guest/google-redirect" method="get" >
				@if(isset($auth_url))
				<input class="btn" type="image" src="/img/sign-in-with-fitm.png" alt="Sign in with FITM 2.0"  style="width:246px;height:54px;"/>
				<input type="hidden" name="auth_url" value="{{$auth_url}}">
				<input type="hidden" name="auth_code" value="{{$auth_code}}">
				@endif
			</form>	
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script src="/js/heartcode-canvasloader-min.js" type="text/javascript"></script>
		<script>
			var init = {{isset($init) ? 'true' : 'false'}};
			var request,count=0;
			var cl = new CanvasLoader('loading');
			$(document).ready(function(){
	
				if(init){	
					
					cl.setColor('#F47063'); // default is '#000000'
					cl.setShape('spiral'); // default is 'oval'
					cl.setDiameter(132); // default is 40
					cl.setDensity(12); // default is 40
					cl.setRange(1.9); // default is 1.3
					cl.setSpeed(1); // default is 2
					cl.setFPS(25); // default is 24
					cl.show(); // Hidden by default
					$('.overlay').fadeIn('slow');
					initial();
				}

			});
			
			function initial(){
				var serializedData = "";
				serializedData += "_method=get";
				serializedData += "&_rand="+encodeURIComponent(Math.random());
				if(request) request.abort();
				request = $.ajax({
					url: "{{action('GuestController@getInitinfo')}}",
					type: "get",
					dataType:"json",
					data:serializedData
				});	
				request.done(function (response, textStatus, jqXHR){
					if(response.status){
						$('.overlay').fadeOut('slow');
					}
					else{
						console.log(count);
						if(count<15){
							setTimeout(initial,1000);
						}
						else{
							cl.hide();
							$('.message').animate({opacity:0},500,function(){ $(this).html('Error can not initialize your information.').css('color','red');}).animate({opacity:1},500);
						}
						count++;
					}
					console.log(response);
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
				
			}
		</script>
	</body>
</html>