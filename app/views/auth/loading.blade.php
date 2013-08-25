<!DOCTYPE html>
<html>
	<head>
		<title>Loading User infomation</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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
			#loading{
				margin-top:100px;
			}
		</style>
		<link rel="shortcut icon" href="/img/ico.png" type="image/x-icon" />
		<link href="/css/auth.css" rel="stylesheet" media="screen">
	</head>
	<body >
		<div class="overlay" >
			<div id="loading" style="width:200px;height:200px;position:relative;margin:100px auto 10px auto" ></div>
			<h1 class="message" >Please wait ...</h1>
		</div> 

		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script src="/js/heartcode-canvasloader-min.js" type="text/javascript"></script>
		<script>
			var cl = new CanvasLoader('loading');
			var timer;
			$(document).ready(function(){
				cl.setColor('#F47063'); // default is '#000000'
				cl.setShape('spiral'); // default is 'oval'
				cl.setDiameter(132); // default is 40
				cl.setDensity(12); // default is 40
				cl.setRange(1.9); // default is 1.3
				cl.setSpeed(1); // default is 2
				cl.setFPS(25); // default is 24
				cl.show(); // Hidden by default
				@if($flag=='signin')
					timer = setTimeout(check,1000);
				@else
					setTimeout(function(){ window.location.href ="{{$url}}"; },3000);
				@endif
			});	
			
			function check(){
				var serializedData = "";
				serializedData += "&_rand="+encodeURIComponent(Math.random());
				var request = $.ajax({
						url: "http://fitmwifi.ap01.aws.af.cm/server/online.php",
						type: "get",
						data:serializedData
				});	
				request.done(function (response, textStatus, jqXHR){
					console.log(response+' '+textStatus+' '+jqXHR);
					clearTimeout(timer);
					window.location.href ="{{$url}}";
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
					
				});
				request.always(function () {
					
				});
				timer = setTimeout(check,1000);
			}
		</script>
	</body>
</html>
