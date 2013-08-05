<!DOCTYPE HTML>
<html>
	<head>
		<title>Userinfo</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >
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
		<link rel="stylesheet" href="/css/pure-min.css" />
		<link rel="stylesheet" href="/css/grids-min.css" />
		<link rel="stylesheet" href="/css/font.css" />
		<link rel="stylesheet" href="/css/animations.css" />
		<link rel="stylesheet" href="/css/overide2.css" />
		<style>
			<?php  $back_num=rand(1,4); ?>
			body{
				background: url(/img/bg{{$back_num}}.jpg) no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg{{$back_num}}.jpg', sizingMethod='scale');
				-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/img/bg{{$back_num}}.jpg', sizingMethod='scale')";
			}
		</style>
	</head>
	<body>
		
		<div class="navicon toggle">
			<img src="/img/navicon-bg.png" width="20px">
		</div>
		<div class="signout">
			<span aria-hidden="true" data-icon="&#xe005;" alt="Signout"></span>
		</div>
		<div class="navigator hidden">
			<header >
				<img class="banner" src="/img/fitm-en.png" width="150">
			</header>
			<ul>
				<li class="blue">
					<a href="#home"><div class="label"></div> <span class="icon" aria-hidden="true" data-icon="&#xe001;" alt="Home"></span>หน้าหลัก </a>
				</li>				
				<li class="green">
					<a href="#user"><div class="label"></div> <span class="icon" aria-hidden="true" data-icon="&#xe003;" alt="User"></span>ผู้ใช้งาน </a>
				</li>				
				<li class="yellow">
					<a href="#statistic"><div class="label"></div> <span class="icon" aria-hidden="true" data-icon="&#xe000;" alt="Statistic"></span>สถิติ </a>
				</li>				
				<li class="pink">
					<a href="#history"><div class="label"></div> <span class="icon" aria-hidden="true" data-icon="&#xe004;" alt="History"></span>ประวัติการใช้ </a>
				</li>
			</ul>
		</div>
		<div class="timer">
			<span class="icon" aria-hidden="true" data-icon="&#xe002;" alt="Home"></span><span>Time Remain : </span><span id="time-remain">00:00:00</span>
		</div>
		<div class="container" id="home" style="display:none">
			<div class="profile">
				<img src="{{ $img }}" class="img-circle profile-img" ></img>
			</div>			
			<div class="info">
				<div class="content">
					<h1 id="name" >{{ $name }}</h1>
					<h5 id="email" >Email : {{ $email }}</h5>
					<h5 id="time" >Login at : {{ $login_at }}</h5>
				</div>
			</div>
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript" > </script>
		<script type="text/javascript">
			var mobile;
			var mac = '{{Session::get('id')}}';
			var google_id = '{{$google_id}}';
			var remain_time = {{$remain_time}}*1000;
			
			$(document).ready(function(){
				
				$('.navigator a').on('click',function(event){
					var element = $(this);
					if(!element.parent().hasClass('selected')){
						$('.navigator li').removeClass('selected');
						element.parent().addClass('selected');
						$('.container').fadeOut('fast',function(){
							$(element.attr('href')).fadeIn('fast');
							if(mobile)$('.navicon').click();
						});
					}
				});
				
				$('.navicon').on('click',function(event){
					if($('.navicon').hasClass('toggle')){
						$('.navicon').removeClass('toggle');
						$('.navigator').removeClass('hidden');
					}
					else{
						$('.navicon').addClass('toggle');
						$('.navigator').addClass('hidden');
					}
				});
				$('.signout').on('click',function(event){
					window.location.href ="{{action('GuestController@getSignout')}}";
				});
				
				if(window.location.hash){
					var hash = window.location.hash;
					$('.navigator a[href="'+hash+'"]').click();
				}
				else{
					$('.navigator a[href="#home"]').click();
				}
				
				checkDevice($(window).width());
				$(window).resize(function(){
					checkDevice($(this).width());
					console.log($(this).width()+' '+$(this).height());
				});
				renderTime();
			});
			
			function renderTime() {
				var msec = remain_time;
				var h = Math.floor(msec / 1000 / 60 / 60);
				msec -= h * 1000 * 60 * 60;
				var m = Math.floor(msec / 1000 / 60);
				msec -= m * 1000 * 60;
				var s = Math.floor(msec / 1000);
				msec -= s * 1000;
				remain_time = remain_time - 1000;
				setTimeout('renderTime()',1000);
				if (h < 10) {
					h = "0" + h;
				}
				if (m < 10) {
					m = "0" + m;
				}
				if (s < 10) {
					s = "0" + s;
				}
				var myClock = document.getElementById('time-remain');
				myClock.textContent = h + ":" + m + ":" + s + " ";
				myClock.innerText = h + ":" + m + ":" + s + " ";
			}
			
			function checkDevice(width){
				if(width>1024){
					if($('.navicon').hasClass('toggle')){
						$('.navicon').addClass('hidden').click();
					}
					mobile=false;
				}
				else{
					if(!$('.navicon').hasClass('toggle')){
						$('.navicon').removeClass('hidden').click();
					}
					mobile=true;
				}	
			}
		</script>
	</body>
</html>