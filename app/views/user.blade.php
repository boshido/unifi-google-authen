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
		<link href="/css/font.css" rel="stylesheet" media="screen">
		<style>
			html{
				background-color:rgb(240, 240, 240);
			}
		</style>
	</head>
	<body>
		<div class="menu">
			<div class="menu-content">
				<div class="banner">fitm</div>
				<div class="sign-out"><div class="menu-btn" id="sign-out">Sign Out</div></div>
				<div class="timer hidden-phone"><span>Time Remain : </span><span id="time-remain">11:00:00</span></div>
			</div>	
		</div>	
		<div class="pure-g cover" >
			<div class="pure-u-1" style="overflow:hidden;">
				<!--<img class="cover-img" alt="Cover photo" src="https://lh6.googleusercontent.com/-0J9hpa4mLeM/UbMLzcWV0jI/AAAAAAAAAMQ/v9em9XOWv6g/s1907-fcrop64=1,00000000ffffadab/rod-luff-fantasy-painting-art-bird-nest-head-nature-elf-beautiful.jpg"> -->
				<img class="cover-img" alt="Cover photo" src="/img/bg{{rand(1,4)}}.jpg">
			</div>
			<div class="shadow-fade">
			</div>
			<div class="bar">
				<div class="pure-g-r" style="padding:10px 10px 20px 10px">
					<div class="pure-u-2-5 profile">
						<img src="{{ $img }}" class="img-circle profile-img" ></img>
					</div>			
					<div class="pure-u-3-5 info">
						<h1 id="name" >{{ $name }}</h1>
						<h5 id="email" >Email : {{ $email }}</h5>
						<h5 id="time" >Login at : {{ $login_at }}</h5>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="sub-menu hide">
				<div class="menu-box">
					<div class="icon" aria-hidden="true" data-icon="&#xe001;" alt="Home"></div>
				</div>				
				<div class="menu-box">
					<div class="icon" aria-hidden="true" data-icon="&#xe003;"></div>
				</div>				
				<div class="menu-box">
					<div class="icon" aria-hidden="true" data-icon="&#xe000;"></div>
				</div>				
				<div class="menu-box">
					<div class="icon" aria-hidden="true" data-icon="&#xe004;"></div>
				</div>
			</div>
			<div class="card" id="home">
				<h3 >Home</h3>
	
			</div>
			<div class="card" id="history">
				<h3>History</h3>
				<table class="pure-table pure-table-horizontal">
					<thead style="background:rgba(244, 112, 99,0.5);">
						<tr >
							<th class="center" >Time</th>
							<th class="center" >Host Name</th>
						</tr>
					</thead>
					<tbody>
							
					</tbody>
				</table>
			</div>
			<div class="card" id="stat-daily">
				<h3>Daily Statistic</h3>
				<div class="pure-g" id="stat-daily">
					<div class="pure-u-1-2 center" style="height:30px;line-height:30px">
						<div class="box-label" style="background:rgba(28, 184, 65,0.5);border-color:rgba(28, 184, 65,1);"></div>
						Download		
					</div>					
					<div class="pure-u-1-2 center" style="height:30px;line-height:30px">
						<div class="box-label" style="background:rgba(248, 148, 34,0.5);border-color:rgba(248, 148, 34,1);"></div>
						Upload
					</div>
				</div>
				<canvas id="daily" width="310" height="300"></canvas>
			</div>
			<div class="card" id="stat-summary">
				<h3>Summary Statistic</h3>
				<div class="pure-g" style="margin-top:5px;">
					<div class="pure-u-1-2 center" style="height:30px;line-height:30px">
						<div class="box-label" style="background:rgba(28, 184, 65,0.5);border-color:rgba(28, 184, 65,1);"></div>
						Download		
					</div>					
					<div class="pure-u-1-2 center" style="height:30px;line-height:30px">
						<div class="box-label" style="background:rgba(248, 148, 34,0.5);border-color:rgba(248, 148, 34,1);"></div>
						Upload
					</div>
				</div>
				<canvas id="summary" width="310" height="300"></canvas>
			</div>
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
			var mac = '{{Session::get('id')}}';
			var google_id = '{{$google_id}}';
			var remain_time = {{$remain_time}}*1000;
			$(document).ready(function(){
				
				$('#sign-out').click(function(){
					window.location.href ="{{action('GuestController@getSignout')}}";
				});
				$(window).resize(function() {
					console.log($(this).width()+' '+$(this).height());
				});
				renderTime();
				daily();
				summary();
				history();
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
			
			function daily(){
				var tomorrow = new Date();
				tomorrow.setDate(tomorrow.getDate()-3);
			
				var request = $.ajax({
						url: "{{action('UnifiController@getStatDaily')}}",
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
					
						graph.date[y] = getDate(response[y].date*1000);
						graph.data.tx[y] = response[y].tx_bytes;
						graph.data.rx[y] = response[y].rx_bytes;
					}
					var max = Math.max(Math.max.apply(Math, graph.data.tx),Math.max.apply(Math, graph.data.rx));
					var label;
					if(parseInt(max/1073741824) > 0){
						for(var y in graph.data.tx){
							graph.data.tx[y] = graph.data.tx[y] / 1073741824;
							graph.data.rx[y] = graph.data.rx[y] / 1073741824;
						}
						label = '<%=value%> GB';
					}
					else if(parseInt(max / 1048576) > 0){
						for(var y in graph.data.tx){
							graph.data.tx[y] = graph.data.tx[y] / 1048576;
							graph.data.rx[y] = graph.data.rx[y] / 1048576;
						}
						label = '<%=value%> MB';
					}					
					else if(parseInt(max / 1024) > 0){
						for(var y in graph.data.tx){
							graph.data.tx[y] = graph.data.tx[y] / 1024;
							graph.data.rx[y] = graph.data.rx[y] / 1024;
						}
						label = '<%=value%> KB';
					}
					else{
						label = '<%=value%> Bytes';
					}
					
					var data = {
						labels : graph.date,
						datasets : [
							{
								fillColor : "rgba(28, 184, 65,0.5)",
								strokeColor : "rgba(28, 184, 65,1)",
								data : graph.data.tx
							},
							{
								fillColor : "rgba(248, 148, 34,0.5)",
								strokeColor : "rgba(248, 148, 34,1)",
								data : graph.data.rx
							}
						],
						showTooltips:true
					}
					
					var ctx = $("#daily").get(0).getContext("2d");
					var barChart = new Chart(ctx).Bar(data,{scaleShowLabels : true,scaleLabel : label});
					
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			
			function summary(){
				var request = $.ajax({
						url: "{{action('UnifiController@getStatSummary')}}",
						type: "get",
						dataType: "json",
						data:{
							type:'user',
							data:mac,
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					console.log(response);
					var max = Math.max(response.tx_bytes,response.rx_bytes);
					var label;
					if(parseInt(max/1073741824) > 0){
						response.tx_bytes = response.tx_bytes / 1073741824;
						response.rx_bytes = response.rx_bytes / 1073741824;
						label = 'GB';
					}
					else if(parseInt(max / 1048576) > 0){
						response.tx_bytes = response.tx_bytes / 1048576;
						response.rx_bytes = response.rx_bytes / 1048576;
						
						label = 'MB';
					}					
					else if(parseInt(max / 1024) > 0){
						response.tx_bytes = response.tx_bytes / 1024;
						response.rx_bytes = response.rx_bytes / 1024;
						label = 'KB';
					}
					else{
						label = 'Bytes';
					}
					
					data = [
						{
							value : response.rx_bytes,
							color : "rgba(248, 148, 34,0.7)"
						},
						{
							value: response.tx_bytes,
							color:"rgba(28, 184, 65,0.7)"
						}
					];
					var ctx = $("#summary").get(0).getContext("2d");
					var donutChart = new Chart(ctx).Doughnut(data,{onAnimationComplete :function(){ draw(response.tx_bytes,response.rx_bytes,label); }});

				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			function draw(tx,rx,label){
				var ctx = $("#summary").get(0).getContext("2d");
				ctx.font = "normal 12px Arial";
				ctx.textAlign = 'center';
				ctx.fillStyle = "rgba(248, 148, 34,1)";
				ctx.fillText(rx.toFixed(2)+' '+label, 155, 140);
				ctx.fillStyle = "rgba(28, 184, 65,1)";
				ctx.fillText(tx.toFixed(2)+' '+label, 155, 160);
			}
			
			function history(){
				var request = $.ajax({
						url: "{{action('UnifiController@getHistory')}}",
						type: "get",
						dataType: "json",
						data:{
							google_id:google_id,
							limit:10,
							sort:'timestamp',
							sort_type:-1,
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					for(var y in response){
						var r = $('<tr></tr>');
						var c_time = $('<td></td>').html(getDate(response[y].timestamp*1000)+" "+getTime(response[y].timestamp*1000)).css('width','100px').appendTo(r);
						var hostname = $('<td></td>').html(response[y].hostname).appendTo(r);
						//var mac = $('<td></td>').html(response[y].mac).appendTo(r);
						
						$('#history table > tbody').append(r);
					}
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}

			function getDate(parameter){
				var d = new Date(parameter); //Unix Timestamp millisecond
				var curr_date = d.getDate();
				var curr_month = d.getMonth() + 1; //Months are zero based
				var curr_year = d.getFullYear()+43-2000;
				return curr_date+'/'+curr_month+'/'+curr_year;
			}				
			function getTime(parameter){
				var d = new Date(parameter); //Unix Timestamp millisecond
				var curr_hours = d.getHours();
				var curr_minutes = d.getMinutes();
				var curr_seconds = d.getSeconds();
				
				if (curr_minutes < 10) curr_minutes = "0" + curr_minutes;
				if (curr_seconds < 10) curr_seconds = "0" + curr_seconds;

				return curr_hours + ":" + curr_minutes + ":" + curr_seconds;
			}
			
		</script>
	</body>
	
</html>