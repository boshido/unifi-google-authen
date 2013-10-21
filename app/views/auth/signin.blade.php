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
			<?php  $back_num=rand(1,4); ?>
			html {
				background: url(/img/bg{{$back_num}}.jpg) no-repeat center center fixed; 
				  -webkit-background-size: cover;
				  -moz-background-size: cover;
				  -o-background-size: cover;
				  background-size: cover;
				  filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg{{$back_num}}.jpg', sizingMethod='scale');
				  -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg{{$back_num}}.jpg', sizingMethod='scale')";
			}
			.btn{
				//filter: blur(3px); 
				//-webkit-filter: blur(3px); 
				//-moz-filter: blur(3px);
				//-o-filter: blur(3px); -ms-filter: blur(3px);
			}
			.overlay-help {
				position: fixed;
				z-index:100;
				top: 0px;
				left: 0px;
				height:100%;
				width:100%;
				background: rgba(0,0,0,0.5);
				display:none;
			}
			.modal{
				position:absolute;
				top:0px;
				border-style:solid;
				border-width:1px;
				border-color:rgb(100, 100, 100);
				margin:5%;
				width:90%;
				height:90%;
				background-color:#FFFFFF;
				z-index:150;
				color:black;
				display:none;
				font-size:14px;
			}
			button.close {
				padding: 0px 4px 0px 0px;
				cursor: pointer;
				background: transparent;
				border: 0;
				-webkit-appearance: none;
			}
			.close{
				position:absolute;
				top:2px;
				right:3px;
				font-size: 20px;
				font-weight: bold;
				line-height: 20px;
				color: #000000;
				text-shadow: 0 1px 0 #ffffff;
				opacity: 0.2;
				filter: alpha(opacity=20);
				z-index:999;
			}
			#faq dt{
				width:50px;
			}
			.text-orange{
				color:rgb(247, 140, 1);
			}
			.underline{
				text-decoration:underline;
			}
		</style>
		<link rel="shortcut icon" href="/img/ico.png" type="image/x-icon">
		<link href="/css/pure-min.css" rel="stylesheet" media="screen">
		<link href="/css/grids-min.css" rel="stylesheet" media="screen">
		<link href="/css/auth.css" rel="stylesheet" media="screen">
		<link href="/css/scrollbar.css" rel="stylesheet" media="screen">
	</head>
	<body>		
		<div class="overlay" style="display:none">
			<div id="loading" style="width:200px;height:200px;position:relative;margin:100px auto 10px auto" ></div>
			<h1 class="message" >Initializing your information.</h1>
		</div>
		<div class="overlay-help"></div>
		<div class="modal">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<div style="height:100%;" class="nano">
				<div class="content" style="padding:0px 22px 0px 10px;">
					<h4 style="margin-left:5px;">คำถามที่พบบ่อย FAQ <form method="get" action="/help.pdf" class="float-right"><input type="submit" style="width:100px;height:28px;"class="menu-btn" value="วิธีการใช้งาน"></form></h4>
					<dl id="faq">
						<dt>คำถาม </dt>
						<dd>หากต้อง Login หรือ Logout ให้ทำอย่างไร</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>ให้ทำการเชื่อมต่อ เข้าไปที่  URL : http://fitmwifi.4th.in/</dd>
						
						<dt>คำถาม </dt>
						<dd>ทำไมต้องมีใช้ e-mail FITM ในการเข้าใช้งาน</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>เพื่อเป็นการยืนยันว่า บุคคลใดเป็นผู้ใช้งาน</dd>
						
						<dt>คำถาม </dt>
						<dd>หากจำ Account หรือ Password ไม่ได้ สามารถติดต่อได้ที่ใด</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>ให้ผู้ใช้งานติดต่อ เจ้าหน้าที่ ที่ห้องคอมพิวเตอร์ 101C</dd>
						
						<dt>คำถาม </dt>
						<dd>หากอุปกรณ์ไม่รองรับการยืนยันตัวตน หากต้องการใช้งานต้องทำอย่างไร</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>ให้ผู้ใช้งานติดต่อ เจ้าหน้าที่ ที่ห้องคอมพิวเตอร์ 101C</dd>
						
						<dt>คำถาม </dt>
						<dd>หลังจากยืนยันตัวตนเข้าใช้งานเสร็จสิ้น สามารถใช้งานโดยตลอดเลยหรือไม่</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>สามารถใช้ได้ 6ชม.  ต่อ ครั้ง หรือ ใช้งานได้ตลอดเวลาหากเลือกจดจำอุปกรณ์</dd>
						
						<dt>คำถาม </dt>
						<dd>ทำไมเมื่อทำการเชื่อมต่อเข้าใช้งาน FITM WiFi พบว่ายังไม่สามารถใช้ Internet ได้ทันทีถึงแม้ได้เคยทำการยืนยันตัวตัวแล้ว</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>ผู้ใช้จำเป็นต้องรอให้ระบบทำการยืนยันตัวตนของผู้ใช้งานกับจุดกระจายสัญญาน เพื่อให้ให้ผู้ใช้งานเข้าใช้งานได้ โดยจะใช้เวลาไม่มาก</dd>
						
						<dt>คำถาม </dt>
						<dd>ทำไมขณะที่ใช่งาน Internet อยู่พบว่าไม่สามารถใช้งาน Internet ในบางครั้ง</dd>
						<dt class="text-orange underline">ตอบ </dt>
						<dd>ให้ผู้ใช้งานทำการเชื่อมต่อเข้า FITM WiFi ใหม่อีกครั้ง</dd>
						
					</dl>
				</div>
			</div>
		</div>
		
		<div class="help"><div class="menu-btn" >Help !</div></div>
		<div class="shadow-fade">
		</div>
		<div class="bar signin text-center">
			<img src="/img/fitm-en.png"  style="width:277.5px;height:145.5px;"></img>
			<div><h1 class="header" >Wi-Fi Authentication</h1></div>
			
			<form action="/guest/google-redirect" method="get" >
				
				<input class="btn" type="image" src="/img/sign-in-with-fitm.png" alt="Sign in with FITM 2.0"  style="width:246px;height:54px;"/>
				<input type="hidden" name="auth_url" value="{{$auth_url}}">
				<input type="hidden" name="auth_code" value="{{$auth_code}}">
				<div class="checkbox"><input type="checkbox" id="remember" name="remember" value="1"> <label for="remember">จดจำอุปกรณ์</label></div>
			</form>	
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript"> </script>
		<script src="/js/scrollbar.js" type="text/javascript" ></script>
		<script src="/js/heartcode-canvasloader-min.js" type="text/javascript"> </script>
		<script>
			var init = {{isset($init) ? 'true' : 'false'}};
			var request,count=0;
			var cl = new CanvasLoader('loading');
			$(document).ready(function(){
	
				$('body').on('click','.close, .overlay-help',function(){
					$('.overlay-help').fadeOut('fast');
					$('.modal').fadeOut('fast');
				});
				$('.help').on('click',function(){
					$('.overlay-help').fadeIn('fast');
					$('.modal').fadeIn('fast',function(){
						$('.nano').nanoScroller({ alwaysVisible: false,preventPageScrolling: true});
					});
				});
				
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
						$('.overlay').fadeOut('slow',function(){window.location.reload();});
					}
					else{
						console.log(count);
						if(count<100){
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
					console.log(count);
					if(count<100){
						setTimeout(initial,1000);
					}
					else{
						cl.hide();
						$('.message').animate({opacity:0},500,function(){ $(this).html('Error can not initialize your information.').css('color','red');}).animate({opacity:1},500);
					}
					count++;
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
				
			}
		</script>
	</body>
</html>