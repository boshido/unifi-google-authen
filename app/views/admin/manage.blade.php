<!DOCTYPE HTML>
<html>
	<head>
		<title>FITM 2.0 Wi-Fi Administrator</title>
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
		<link rel="shortcut icon" href="/img/ico.png" type="image/x-icon" />
		<link rel="stylesheet" href="/css/pure-min.css" />
		<link rel="stylesheet" href="/css/grids-min.css" />
		<link rel="stylesheet" href="/css/font.css" />
		<link rel="stylesheet" href="/css/admin.css" />
		<style>
			html {
				background: url(/img/bg.png) repeat center center fixed; 
			}
			dl.dl-inline {
				color:#FFFFFF;
				margin:0px;
				padding:0px;
				overflow: hidden;
			}
			dl.dl-inline dt, dl.dl-inline dd {
				float: left;
				text-align:center;
			}
			dl.dl-inline dt {
				font-size:15px;
				min-width: 60px;
				margin: 10px 0px;
				padding: 4px;
			}
			dl.dl-inline dd {
				font-size:12px;
				overflow: hidden;
				margin: 5px 0px;
				padding: 4px;
			}
			dl.dl-inline dd p{
				margin:0px;
				padding:0px 10px;
				border-right-style:dotted;
				border-right-width:1px;
				border-right-color:rgb(100,100,100);
			}
		</style>
	</head>
	<body>
		<img class="banner" src="/img/fitm-en.png" width="100">
		<div class="quick-info">
			<dl class="dl-inline">
				<dt>Person </dt>
				<dd><p>66<br>Authorize</p></dd>
				<dt>Ready</dt>
				<dd>Record .</dd>
			</dl>
		</div>
		
	
		<script src="/js/jquery-2.0.3.js" type="text/javascript" > </script>
		<script src="/js/highcharts.js" type="text/javascript" ></script>
		<script src="/js/exporting.js" type="text/javascript" ></script>
		<script type="text/javascript">
		
			var mobile,selected,chart;
			var mac = '';
			var google_id = '';
			var end_time = 1000;
			
			$(document).ready(function(){
				
				// Binding event
				$('.navigator a').on('click',function(event){
					var element = $(this);
					if(!element.parent().hasClass('selected')){
						$('.navigator li').removeClass('selected');
						element.parent().addClass('selected');
						$(selected).fadeOut('fast');
						$(element.attr('href')).fadeIn('fast');
						selected = element.attr('href');
						if(mobile)$('.navicon').click();
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
				
				$('.signout').click(function(){
					window.location.href ="{{action('GuestController@getSignout')}}";
				});
				
				// Checking hash url
				if(window.location.hash){
					var hash = window.location.hash;
					$('.navigator a[href="'+hash+'"]').click();
				}
				else{
					$('.navigator a[href="#home"]').click();
				}
				
				// Initial
				daily();
				session();
				history();
		
				
				
				checkDevice($(window).width(),$(window).height());
				$(window).resize(function(){
					checkDevice($(this).width(),$(this).height());
					console.log($(this).width()+' '+$(this).height());
				});
				
			});
			
			function checkDevice(width,height){
				if(width>1024){
					if($('.navicon').hasClass('toggle')){
						$('.navicon').addClass('hidden').click();
					}
					mobile=false;
					if(chart)chart.setSize(width-200,height-100,true);
				}
				else{
					if(!$('.navicon').hasClass('toggle')){
						$('.navicon').removeClass('hidden').click();
					}
					mobile=true;
					if(chart)chart.setSize(width,height-100,true);
				}	
			}
			
			function renderTime() {
				var now = new Date();
				var msec =  end_time - now.getTime() ;
				var h = Math.floor(msec / 1000 / 60 / 60);
				msec -= h * 1000 * 60 * 60;
				var m = Math.floor(msec / 1000 / 60);
				msec -= m * 1000 * 60;
				var s = Math.floor(msec / 1000);
				msec -= s * 1000;
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
				if(h < 23){
					myClock.textContent = h + ":" + m + ":" + s + " ";
					myClock.innerText = h + ":" + m + ":" + s + " ";
				}
			}
			
			function daily(){
				var tomorrow = new Date();
				tomorrow.setDate(tomorrow.getDate()-9);
			
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
						label = 'GBytes';
					}
					else if(parseInt(max / 1048576) > 0){
						for(var y in graph.data.tx){
							graph.data.tx[y] = graph.data.tx[y] / 1048576;
							graph.data.rx[y] = graph.data.rx[y] / 1048576;
						}
						label = 'MBytes';
					}					
					else if(parseInt(max / 1024) > 0){
						for(var y in graph.data.tx){
							graph.data.tx[y] = graph.data.tx[y] / 1024;
							graph.data.rx[y] = graph.data.rx[y] / 1024;
						}
						label = 'KBytes';
					}
					else{
						label = 'Bytes';
					}
					
					chart = new Highcharts.Chart({
						chart: {
							renderTo: bar,
							backgroundColor:'rgba(255,255,255,0)',
							type: 'column'
						},
						title: {
							text: 'สถิติการใช้งานในแต่ละวัน'
						},
						subtitle: {
							text: ''
						},
						xAxis: {
							categories: graph.date
						},
						yAxis: {
							min: 0,
							title: {	
								text: label
							}
						},
						tooltip: {
							headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
							pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
								'<td style="padding:0"><b>{point.y:.1f} '+label+'</b></td></tr>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true
						},
						plotOptions: {
							column: {
								pointPadding: 0.2,
								borderWidth: 0
							}
						},
						colors:
							[
							   '#2ecc71', 
							   '#f39c12', 
							 
							]
						,
						series: [{
							name: 'ดาวโหลด',
							data: graph.data.tx
				
						}, {
							name: 'อัพโหลด',
							data: graph.data.rx
				
						}]
						,
						credits: {
							enabled: false
						}
					});
					checkDevice($(window).width(),$(window).height());
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			
			function history(){
				var request = $.ajax({
						url: "{{action('UnifiController@getHistory')}}",
						type: "get",
						dataType: "json",
						data:{
							google_id:google_id,
							limit:10,
							sort:'start',
							sort_type:-1,
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					for(var y in response){
						var r = $('<tr></tr>');
						$('<td></td>').html(getDate(response[y].start*1000)+" "+getTime(response[y].start*1000)).appendTo(r);
						$('<td></td>').html(response[y].hostname).appendTo(r);
						
						$('#history > tbody').append(r);
					}
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			
			function session(){
				var request = $.ajax({
						url: "{{action('UnifiController@getActiveSession')}}",
						type: "get",
						dataType: "json",
						data:{
							google_id:google_id,
							limit:10,
							sort:'start',
							sort_type:-1,
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					
					for(var y in response){
						var r = $('<tr session-mac="'+response[y].mac+'"></tr>');
						$('<td></td>').html(getTime(response[y].last_seen*1000)).appendTo(r);
						$('<td></td>').html(response[y].hostname).appendTo(r);
						if(mac == response[y].mac){
							$('<td></td>').html('').appendTo(r);
							$('#session > tbody').prepend(r);
						}
						else{
							$('<td></td>').html('<span class="icon remove" aria-hidden="true" data-icon="&#xe006;" onClick="removeSession(this)" ></span>').appendTo(r);
							$('#session > tbody').append(r);
						}
					}
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			
			function removeSession(element){
				var r = $(element).parent().parent()
				var request = $.ajax({
						url: "{{action('UnifiController@postDeactiveSession')}}",
						type: "post",
						dataType: "html",
						data:{
							mac:r.attr('session-mac'),
							_method:'post',
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					if(response=='1'){
						r.fadeOut();
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