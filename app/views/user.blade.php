<!DOCTYPE html>
<html>
	<head>
		<title>FITM 2.0 Wifi User Info</title>
		
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
		
		<link href="/css/pure-min.css" rel="stylesheet" media="screen"/>
		<link href="/css/grids-min.css" rel="stylesheet" media="screen">
		<link href="/css/overide.css" rel="stylesheet" media="screen">
		<style>
			html{
				background-color:lightgray;
			}
		</style>
	</head>
	<body>
		<div class="menu">
			<div class="menu-content">
				<div class="banner">fitm</div>
				<div class="sign-out"><div class="menu-btn" id="sign-out">Sign Out</div></div>
				<div class="timer"><span>Time Remain :</span>11:00:00</div>
			</div>	
		</div>	
		<div class="pure-g cover" >
			<div class="pure-u-1" style="overflow:hidden;">
				<?php //print_r($user); ?>
				<!--<img class="cover-img" alt="Cover photo" src="https://lh6.googleusercontent.com/-0J9hpa4mLeM/UbMLzcWV0jI/AAAAAAAAAMQ/v9em9XOWv6g/s1907-fcrop64=1,00000000ffffadab/rod-luff-fantasy-painting-art-bird-nest-head-nature-elf-beautiful.jpg"> -->
				<img class="cover-img" alt="Cover photo" src="/img/bg{{rand(1,4)}}.jpg">
			</div>
			<div class="shadow-fade">
			</div>
			<div class="bar">
				<div class="pure-g-r" style="padding:10px 10px 20px 10px">
					<div class="pure-u-2-5 profile">
						<img src="<?php echo $img; ?>" class="img-circle profile-img" ></img>
					</div>			
					<div class="pure-u-3-5 info">
						<h1 id="name" ><?php echo $name?></h1>
						<h5 id="email" >Email : <?php echo $email?></h5>
						<h5 id="time" >Login at : 12:15:00</h5>
					</div>
				</div>
			</div>
		</div>
		<div style="height:200px;text-align:center;">
			<div style="width:186px;height:67px;background-color:white;text-align:center;line-height:67px;margin:50px auto 0px auto;" class="shadow" >
				<h1 style="color:#F47063;" >Comming Soon</h1>
			</div>
			
		</div>
		<div class="card">
			<canvas id="myChart" width="310" height="300"></canvas>
		</div>
		
		<!--
		<div class="pure-g">
			<div class="pure-u-1-3">
				<div class="l-box">
					<h3>Thirds</h3>
					<p>This cell will be a grid even on mobile devices.</p>
				 </div>
			</div>
			<div class="pure-u-1-3">
				<div class="l-box">
					<h3>Thirds</h3>
					<p>This cell will be a grid even on mobile devices.</p>
				 </div>
			</div>
		</div>-->
		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script src="/js/chart.js" type="text/javascript"></script>
		<script>
			var mac = 'c8:3d:97:6c:32:08';
			$(document).ready(function(){
				$('#sign-out').click(function(){
					window.location.href ="{{action('GuestController@getSignout')}}";
				});
				check();
			});
			
			function check(){
			var tomorrow = new Date();
			tomorrow.setDate(tomorrow.getDate()-3);
			
				var request = $.ajax({
						url: "{{action('UnifiController@getHistoryDate')}}",
						type: "get",
						dataType: "json",
						data:{
							mac:mac,
							at:parseInt(tomorrow.getTime()/1000),
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					console.log(response);
				
					var graph = {
							date:[],
							data:{
									tx:[],
									rx:[]
								}
							};
					for(var y in response){
						
						var d = new Date(response[y].date*1000);
						var curr_date = d.getDate();
						var curr_month = d.getMonth() + 1; //Months are zero based
						var curr_year = d.getFullYear()+43-2000;
						
						graph.date[y] = curr_date+'/'+curr_month+'/'+curr_year;
						graph.data.tx[y] = response[y].tx_bytes/1024/1024;
						graph.data.rx[y] = response[y].rx_bytes/1024/1024;
						
					}
					var data = {
						labels : graph.date,
						datasets : [
							{
								fillColor : "rgba(57, 213, 167,0.5)",
								strokeColor : "rgba(57, 213, 167,1)",
								data : graph.data.tx
							},
							{
								fillColor : "rgba(248, 148, 34,0.5)",
								strokeColor : "rgba(248, 148, 34,1)",
								data : graph.data.rx
							}
						]
					}
					
					var ctx = $("#myChart").get(0).getContext("2d");
					var myNewChart = new Chart(ctx).Bar(data);
					
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