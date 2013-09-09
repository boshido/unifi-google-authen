<!DOCTYPE HTML>
<html>
	<head>
		<title>FITM 2.0 Wi-Fi Administrator</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" >
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
		<link rel="stylesheet" href="/css/jquery.dataTables.css" />
		<link rel="stylesheet" href="/css/pure-min.css" />
		<link rel="stylesheet" href="/css/grids-min.css" />
		<link rel="stylesheet" href="/css/font.css" />
		<link rel="stylesheet" href="/css/admin.css" />
		<link rel="stylesheet" href="/css/scrollbar.css" />
		<style>
			html {
				background: url(/img/bg.png) repeat center center fixed; 
				color:rgb(255,255,255);
			}
			a {
				text-decoration:none;
				color:rgb(255,255,255);
			}
			header{
				padding:0px 30px;
				margin-top:50px;
				height:50px;
				line-height:50px;
			}
			.container {
				position:relative;
				margin:0px 40px 40px 40px;
				min-width:600px;
				min-height:480px;
				background-color:rgba(20,20,20,0.7);
				border-style:solid;
				border-width:1px;
				border-color:rgb(100, 100, 100);
			}
			.container > .tab{
				position:absolute;
				top:0px;
				left:0px;
				width:100%;
				height:100%;
				display:none;
			}
			.banner{
				margin:5px 5px 5px 15px;
			}
			.menu-list{
				position:absolute;
				top:-40px;
				left:50%;
				margin-left:-175px;
				height:30px;
				background-color:rgba(50,50,50,0.7);
				z-index:5;
				border-style:solid;
				border-width:1px;

				border-color:rgb(100, 100, 100);
				
			}
			.menu-item-list{
				position:relative;
				float:right;
				text-align:center;
				width:60px;
				padding:0px 5px;
				font-size:14px;
				line-height:30px;
				height:30px;
				z-index:5;
				cursor:pointer;
				text-shadow: 0 1px 1px rgba(40, 40, 40, 0.75);
			}
			.menu-selector{
				position:absolute;
				width:60px;
				padding:0px 5px;
				height:30px;
				background-color:rgb(0, 150, 231);
				z-index:0;
				-moz-box-shadow: 0 0 5px #888;
				-webkit-box-shadow: 0 0 5px#888;
				box-shadow: 0 0 5px #888;

			}
			
			.controller-bar{
				padding:15px 15px 15px 15px;
			}
			
			.pure-table{
				border-width:0px;
			}
			.pure-table > thead > tr > th{
				font-size:13px;
				color:black;
				font-weight:normal;
				text-align:center;
				
			}		
			
			.pure-table > tbody > tr > td{
				font-size:12px;
				font-family: 'Open Sans',arial,sans-serif;
				/*color:rgb(153, 154, 153);*/
				color:rgb(185, 185, 185);
				font-weight:normal;
				border-width:0px;
			}
			
			dl.dl-inline {
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
				margin: 5px 0px 5px 5px;
				padding: 4px;
			}
			dl.dl-inline dt:nth-of-type(n+2) {
				margin-left: 10px;
			}
			dl.dl-inline dd {
				font-size:12px;
				overflow: hidden;
				margin: 5px 0px;
				padding: 4px 3px;
			}
			dl.dl-inline dd p{
				float:left;
				margin:0px;
				padding:0px 10px;
				border-right-style:dotted;
				border-right-width:1px;
				border-right-color:rgb(100,100,100);
			}
			dl.dl-inline dd p.last{	
				border-right-width:0px;
			}
			
			input[type="radio"].toggle {
				display: none;
			}			

			input[type="radio"].toggle:checked + label {
				background-color:rgb(0, 150, 231);
				cursor: default;
				color: #E6E6E6;
				border-color: transparent;
				cursor:pointer;
				-moz-box-shadow: 0 0 5px #888;
				-webkit-box-shadow: 0 0 5px#888;
				box-shadow: 0 0 5px #888;
			}

			input[type="radio"].toggle + label {
				float:right;
				padding:4px 6px 5px 5px;
				margin:0px;
				text-shadow: 0 1px 1px rgba(40, 40, 40, 0.75);
				font-size:12px;
				color:#FFFFFF;
				background-color:rgba(20,20,20,0.7);
				cursor:pointer;
				
			}

			/*input[type="radio"].toggle:checked + label.btn:hover {
				background-color: white;
				
				
			}*/

			input[type="radio"].toggle-left + label {
				/*border-top-left-radius: 3.9914772510528564px;
				border-bottom-left-radius: 3.9914772510528564px;*/
			}

			input[type="radio"].toggle-right + label {
				/*border-top-right-radius: 3.9914772510528564px;
				border-bottom-right-radius: 3.9914772510528564px;*/
			}
			label.head{
				color:rgb(0,0,0);
				font-family: 'Open Sans',arial,sans-serif;
				font-size:13px;
			}
			label.content{
				color:rgb(100,100,100);
				font-weight:normal;
				font-family: 'Open Sans',arial,sans-serif;
				font-size:13px;
			}
			.overlay {
				position: fixed;
				z-index:100;
				top: 0px;
				left: 0px;
				height:100%;
				width:100%;
				background: rgba(0,0,0,0.5);
				display:none
			}
			.modal{
				position:absolute;
				top:50%;
				border-style:solid;
				border-width:1px;
				border-color:rgb(100, 100, 100);
				margin:-250px 5% 0px 5%;
				width:90%;
				height:460px;
				background-color:#FFFFFF;
				display:none;
				z-index:150;
				color:black;
				
			}
			.modal > .modal-header{
				width:100%;
				color:#FFFFFF;
				padding:0px 0px;
				height:40px;
				line-height:40px;
				background-color:rgba(70,70,70,1);
				font-size:14px;
			}
			.modal > .modal-content{
				height:80%;
				width:100%;
				font-size:13px;
				border-style:solid;
				border-width:0px 0px 1px 0px;
				border-color:rgb(100, 100, 100);
				position:relative;
			}
			.modal .modal-content-left{
				width:240px;
				height:100%;
				float:left;
				/*border-style:solid;
				border-width:0px 1px 0px 0px;
				border-color:rgb(100, 100, 100);*/
				overflow:hidden;
				text-overflow: ellipsis;
			}		
			.modal .modal-content-right{
				width:auto;
				height:100%;
				margin:0px;
				overflow:auto;
			}
			.modal-item-list{
				height:100%;
				width:100px;
				padding:0px 10px;
				color:rgb(170,170,170);
				text-align:center;
				cursor:pointer;
				float:left;
			}
			.modal-item-list.selected{
				color:rgb(0,0,0);
				background-color:rgb(255, 255, 255);
			}
			button.close {
				padding: 0px 4px 0px 0px;
				cursor: pointer;
				background: transparent;
				border: 0;
				-webkit-appearance: none;
			}
			.close{
				float: right;
				font-size: 20px;
				font-weight: bold;
				line-height: 20px;
				color: #000000;
				text-shadow: 0 1px 0 #ffffff;
				opacity: 0.2;
				filter: alpha(opacity=20);
			}
			ul.list{
				margin:0px;
				list-style-type:none;
				padding:0px;
			}
			ul.list > li{
				padding:10px 30px;
				cursor:pointer;
				
			}
			ul.list > li > img{
				width:22px;
				height:22px;
				margin-right:5px;
				float:left;
			}
			ul.list > li.selected{
				cursor:pointer;
				color:black;
				background:url('/img/admin/two-arrow.png') no-repeat center right;
			}			
			ul.list > li.selected:hover{
				background:rgb(0, 150, 231) url('/img/admin/two-arrow-white.png') no-repeat center right;
			}
			ul.list > li:hover{
				color:white;
				background-color:rgb(0, 150, 231);
			}
			ul.list > li:hover img.limited{
				content:url('/img/admin/time-white.png');
			}
			ul.list > li:hover img.unlimited{
				content:url('/img/admin/infinity-white.png');
			}
		</style>
	</head>
	<body>
		<div class="quick-info">
			<img class="banner float-left" src="/img/fitm-en.png" width="80">
			<dl class="dl-inline float-left">
				<dt>Access Points </dt>
				<dd>
					<p><span class="text-inform" id="connected"></span><br><span class="text-muted">connected</span></p>
					<p><span class="text-alert"  id="disconnected"></span><br><span class="text-muted">disconnected</span></p>
				</dd>
				<dt>Device</dt>
				<dd>
					<p><span class="text-inform" id="authorized"></span><br><span class="text-muted">authorized</span></p>
					<p class="last"><span class="text-alert" id="non-authorized"></span><br><span class="text-muted">non-authorized</span></p>
				</dd>
				
			</dl>
		</div>
		<header>
			<div class="float-left">Welcome  
				<span  class="text-success">{{Session::get('login')}}</span>  | 
				<a href="{{action('AdminController@getSignout')}}" style="font-size:14px;" >settings</a> | 
				<a href="{{action('AdminController@getSignout')}}" style="font-size:14px;" >sign out</a>
			</div>
		</header>

		<div class="container">
			<div class="menu-list">
				<a class="menu-item-list " href="#setting">
					Setting
				</a>
				<a class="menu-item-list" href="#authorize">
					Authorize
				</a>
				<a class="menu-item-list" href="#ap">
					AP
				</a>
				<a class="menu-item-list" href="#device">
					Device
				</a>
				<a class="menu-item-list selected" href="#user">
					User
				</a>
				<div class="menu-selector"> </div>
			</div>
			<div class="tab" id="user" >
				<div class="pure-form controller-bar">
					<label for="search" style="width:40px;color:white;margin-right:5px;">Search</label> 
					<input class="search" name="search" type="text" style="height:25px;padding:5px;">
					
					<input id="toggle-all" 	class="toggle toggle-right" name="user-toggle" value="true" 	type="radio" ><label for="toggle-all" >All</label
					><input id="toggle-offline" class="toggle" 				name="user-toggle" value="false" 	type="radio" ><label for="toggle-offline" style=""  >Offline</label
					><input id="toggle-online" 	class="toggle toggle-left" 	name="user-toggle" value="false" 	type="radio" checked><label for="toggle-online" >Online</label
					>
				</div>
			
				<table class="pure-table" id="user-table" style="width:100%;">
					<thead>
						<tr>
							<th>Google ID</th>
							<th>Email</th>
							<th>Full Name</th>
							<th>Device</th>
							<th>Authorized</th>
							
						</tr>
					</thead>
					<tbody>
						
						
					</tbody>			
				</table>
				<div class="modal">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="modal-header">
						<div class="modal-content-left">
							<img src="/img/admin/device2.png"  style="width:35px;height:25px;margin:5px 5px 0px 10px;float:left;"></img><span id="owner"></span>
						</div>
						<div class="modal-content-right">
							<div class="modal-item-list selected">
								User Information
							</div>
							<div class="modal-item-list">
								Statistic
							</div>
							<div class="modal-item-list ">
								History
							</div>
						</div>
					</div>
					<div style="width:100%;border-style:solid;border-width:1px 0px;border-color:rgb(247, 178, 42)"></div>
					<div class="modal-content">
						<div class="modal-content-left nano">
							<div class="content">
								<ul class="list" id="device-list">
									<li class="selected">
										<img src="/img/admin/infinity.png"   class="limited" ></img>Boshido-Laptop
									</li>	
									<li>
										<img src="/img/admin/time.png"  class="unlimited" ></img>Gnowman-IPAD
									</li>
								</ul>
							</div>
						</div>
						<div class="modal-content-right" style="font-family:'Open Sans',arial,sans-serif;font-weight:normal;padding-left:20px;">
							<p>Session Information</p>
							<div style="">
								<img src="/img/admin/download.png"  style="width:50px;height:50px" ></img>
							</div>
							
						</div>
					</div>
					<div class="modal-footer">
						<button class="pure-button pure-button-primary">Unauthorize</button>
					</div>
					
					<!-- <label class="head">Device Name : </label><label class="content">Boshido-LAPTOP </label><br>
						<label class="head">Authentication Type : </label><label class="content">Unlimited</label><br>
						<label class="head">Start : </label><label class="content">8-9-2556</label><br>
						<label class="head">End : </label><label class="content">12-12-3000 </label><br>
						-->
				</div>
			</div>
			<div class="tab" id="device" >
			</div>
			<div class="tab" id="ap" >
			</div>
			<div class="tab" id="authorize" >
			</div>
			<div class="tab" id="setting" >
			</div>
		</div>
		<div class="overlay">
			
		</div>
		<script src="/js/jquery-2.0.3.js" type="text/javascript" > </script>
		<script src="/js/highcharts.js" type="text/javascript" ></script>
		<script src="/js/exporting.js" type="text/javascript" ></script>
		<script src="/js/jquery.dataTables.min.js" type="text/javascript" ></script>
		<script src="/js/jquery.dataTables.plugin.js" type="text/javascript" ></script>
		<script src="/js/scrollbar.js" type="text/javascript" ></script>
		<script type="text/javascript">
			var user_table,selected,user_sta={};
			$(document).ready(function(){
			 
				user_table = $('#user-table').dataTable( {
					"sDom": "<r><t><i><p>",
					"sPaginationType": "full_numbers",
					"bAutoWidth":false,
					"iDisplayLength": 15,
					"fnServerParams": function ( aoData ) {
						aoData.push( { "name": "key", "value": "element" } );
					},
					//"sAjaxSource": "{{action('UnifiController@getUserTable')}}",
					"aoColumns":[
						{"mDataProp":"google_id"},
						{"mDataProp":"email"},
						{"mDataProp":"name","sDefaultContent": ""},			
						{	
							"sDefaultContent": '<span class="text-alert">None</span>',
							"bSortable": false,
							"mRender": function (data, type, full) {		
								console.log(full);
								if(full.status == 'Online'){
									var tmp = {};
									tmp.device = full.device;
									tmp.name = full.name
									return '<a class="modal-button text-success" href="#" data=\''+JSON.stringify(tmp)+'\'">Click !<a>';
								}
							},
							"sClass":'text-center'
						},
						{"mDataProp":"status","sDefaultContent": "Offline",'bVisible':false}
					]
				} );
				user_table.fnFilter( 'Online',4);
				$('#user .search').keyup(function(){
					user_table.fnFilter( $(this).val() );
				});
				$('#user .toggle').on('change',function(){
					if($(this).attr('id')=='toggle-online') user_table.fnFilter( 'Online',4);
					else if($(this).attr('id')=='toggle-offline') user_table.fnFilter( 'Offline',4);
					else if($(this).attr('id')=='toggle-all') user_table.fnFilter( '',4);
				});
				$('#user').on('click','.modal-button',function(event){
					console.log($(this).parent());
					$('#device-list').empty()
					var user = JSON.parse($(this).attr('data'));
					$('#owner').html(user.name+"'s Device");
					//{auth_type: 1, mac: "8c:fa:ba:7a:81:cf", start: 1378106032, end: 1978106062, hostname: "Gnowman-iPad"}
					for(var y in user.device){
						var img,li;
						if(user.device[y].auth_type==0) img = $('<img src="/img/admin/time.png" ></img>').addClass('limited');
						else img = $('<img src="/img/admin/infinity.png" ></img>').addClass('unlimited');

						$('#device-list').append($('<li class="device-item-list"></li>').attr('mac',user.device[y].mac).append(img).append(typeof(user.device[y].hostname) != 'undefined' ? user.device[y].hostname : user.device[y].mac));
					}
					
					
					$('.overlay').fadeIn('fast').click(function(){ $(this).fadeOut('fast');$('.modal').fadeOut('fast');});
					$('.modal').fadeIn('fast');
					
					$(".nano").nanoScroller({ 
						alwaysVisible: false,
						preventPageScrolling: true
					});	
				});
				
				$('body').on('click','.close',function(){
					$('.overlay').fadeOut('fast');
					$('.modal').fadeOut('fast');
				});
				$('#device-list').on('click','.device-item-list',function(){
					$('.device-item-list').removeClass('selected');
					$(this).addClass('selected');
					console.log(user_sta[$(this).attr('mac')]);
				});
				initial();
				$(window).resize(function(){
					initial();
					console.log($(this).width()+' '+$(this).height());
				});
				
				$('.menu-item-list').on('click',function(){
					var element = $(this);
					if($(element.attr('href')).css('display')=='none'){
						$('.menu-item-list').removeClass('selected');
						element.addClass('selected');
						//$(selected).fadeOut();
						$(selected).hide();
						//$(element.attr('href')).fadeIn();
						$(element.attr('href')).show();
						selected = element.attr('href');
					}
					initial();
				});
				
				// Checking hash url
				if(window.location.hash){
					var hash = window.location.hash;
					$('.menu-item-list[href="'+hash+'"]').click();
				}
				else{
					$('.menu-item-list[href="#user"]').click();
				}
				
				// Initial
				loading();
			});
			
			function initial(){
				var position = $('.menu-item-list.selected').position();
				$('.menu-selector').css('top',position.top+'px').css('left',position.left+'px').addClass('transition');
				
			}
			
			function loading(){
				device();		
				user();
				user_table.fnReloadAjax('{{action('UnifiController@getUserTable')}}',function(parameter){console.log(parameter)},true);
				setTimeout(loading,5000);
			}
			
			function device(){
				var request = $.ajax({
						url: "{{action('UnifiController@getDevice')}}",
						type: "get",
						dataType: "json",
						data:{
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					
					response=response.data;
					var connected=0,disconnected=0;
					for(var y in response){
						if(response[y].state==1) connected++;
						else disconnected++;
					}
					$('#connected').html(connected);
					$('#disconnected').html(disconnected);
				});	
				request.fail(function (jqXHR, textStatus, errorThrown){
					console.log("The following error occured: "+textStatus, errorThrown);
				});
				request.always(function () {
					
				});
			}
			
			function user(){
				var request = $.ajax({
						url: "{{action('UnifiController@getUserList')}}",
						type: "get",
						dataType: "json",
						data:{
							_rand:encodeURIComponent(Math.random())
						}
				});	
				request.done(function (response, textStatus, jqXHR){
					$('#authorized').html(response.authorized);
					$('#non-authorized').html(response.non_authorized);
					for(var y in response.data){
						user_sta[response.data[y].mac]=response.data[y];
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