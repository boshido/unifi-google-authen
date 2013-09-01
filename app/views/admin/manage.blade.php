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
		<link rel="stylesheet" href="/css/jquery.dataTables.css" />
		<link rel="stylesheet" href="/css/pure-min.css" />
		<link rel="stylesheet" href="/css/grids-min.css" />
		<link rel="stylesheet" href="/css/font.css" />
		<link rel="stylesheet" href="/css/admin.css" />
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
				margin:0px 40px 0px 40px;
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
			.menu{
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
			.menu-list{
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
				font-family: 'Open Sans',arial,sans-serif;;
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
				<dt>User</dt>
				<dd>
					<p><span class="text-inform" id="authorized"></span><br><span class="text-muted">authorized</span></p>
					<p class="last"><span class="text-alert" id="non-authorized"></span><br><span class="text-muted">non-authorized</span></p>
				</dd>
				
			</dl>
		</div>
		<header>
			<div class="float-left">Welcome  
				<span  style="color:#9fd834;">{{Session::get('login')}}</span>  | 
				<a href="{{action('AdminController@getSignout')}}" style="font-size:14px;" >settings</a> | 
				<a href="{{action('AdminController@getSignout')}}" style="font-size:14px;" >sign out</a>
			</div>
		</header>
		<div class="container">
			<div class="menu">
				<a class="menu-list " href="#setting">
					Setting
				</a>
				<a class="menu-list" href="#authorize">
					Authorize
				</a>
				<a class="menu-list" href="#ap">
					AP
				</a>
				<a class="menu-list" href="#device">
					Device
				</a>
				<a class="menu-list selected" href="#user">
					User
				</a>
				<div class="menu-selector"> </div>
			</div>
			<div class="tab" id="user" >
				<div class="pure-form controller-bar">
					<label for="user-search" style="width:40px;color:white;margin-right:5px;">Search</label> 
					<input id="user-search" name="user-search" type="text" style="height:25px;padding:5px;">
					
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
		<script src="/js/jquery-2.0.3.js" type="text/javascript" > </script>
		<script src="/js/highcharts.js" type="text/javascript" ></script>
		<script src="/js/exporting.js" type="text/javascript" ></script>
		<script src="/js/jquery.dataTables.min.js" type="text/javascript" ></script>
		<script type="text/javascript" >
			$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
			{
				if ( sNewSource !== undefined && sNewSource !== null ) {
					oSettings.sAjaxSource = sNewSource;
				}
			 
				// Server-side processing should just call fnDraw
				if ( oSettings.oFeatures.bServerSide ) {
					this.fnDraw();
					return;
				}
			 
				this.oApi._fnProcessingDisplay( oSettings, true );
				var that = this;
				var iStart = oSettings._iDisplayStart;
				var aData = [];
			 
				this.oApi._fnServerParams( oSettings, aData );
			 
				oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
					/* Clear the old information from the table */
					that.oApi._fnClearTable( oSettings );
			 
					/* Got the data - add it to the table */
					var aData =  (oSettings.sAjaxDataProp !== "") ?
						that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;
			 
					for ( var i=0 ; i<aData.length ; i++ )
					{
						that.oApi._fnAddData( oSettings, aData[i] );
					}
					 
					oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
			 
					that.fnDraw();
			 
					if ( bStandingRedraw === true )
					{
						oSettings._iDisplayStart = iStart;
						that.oApi._fnCalculateEnd( oSettings );
						that.fnDraw( false );
					}
			 
					that.oApi._fnProcessingDisplay( oSettings, false );
			 
					/* Callback user function - for event handlers etc */
					if ( typeof fnCallback == 'function' && fnCallback !== null )
					{
						fnCallback( oSettings );
					}
				}, oSettings );
			};
		</script>
		<script type="text/javascript">
			var user_table;
			var selected;
			$(document).ready(function(){
				user_table = $('#user-table').dataTable( {
					"sDom": "<r><t><i><p>",
					"sPaginationType": "full_numbers",
					"bAutoWidth":false,
					"iDisplayLength": 15,
					"fnServerParams": function ( aoData ) {
						aoData.push( { "name": "key", "value": "element" } );
					},
					"sAjaxSource": "{{action('UnifiController@getUserTable')}}",
					"aoColumns":[
						{"mDataProp":"google_id"},
						{"mDataProp":"email"},
						{"mDataProp":"name","sDefaultContent": ""},			
						{	
							"sDefaultContent": "aef",
							"mRender": function (data, type, full) {		
								
							}
						},
						{"mDataProp":"status","sDefaultContent": "Offline",'bVisible':false}
					]
				} );
				user_table.fnFilter( 'Online',4);
				$('#user-search').keyup(function(){
					user_table.fnFilter( $(this).val() );
				});
				
				$('#user .toggle').on('change',function(){
					if($(this).attr('id')=='toggle-online') user_table.fnFilter( 'Online',4);
					else if($(this).attr('id')=='toggle-offline') user_table.fnFilter( 'Offline',4);
					else if($(this).attr('id')=='toggle-all') user_table.fnFilter( '',4);
				});
				
				$('.navigator a').on('click',function(event){
					
				});
				
				initial();
				$(window).resize(function(){
					initial();
					console.log($(this).width()+' '+$(this).height());
				});
				
				$('.menu-list').on('click',function(){
					var element = $(this);
					if($(element.attr('href')).css('display')=='none'){
						$('.menu-list').removeClass('selected');
						element.addClass('selected');
						$(selected).fadeOut('fast');
						$(element.attr('href')).fadeIn('fast');
						selected = element.attr('href');
					}
					initial();
				});
				
				// Checking hash url
				if(window.location.hash){
					var hash = window.location.hash;
					$('.menu-list[href="'+hash+'"]').click();
				}
				else{
					$('.menu-list[href="#user"]').click();
				}
				
				// Initial
				loading();
			});
			
			function initial(){
				var position = $('.menu-list.selected').position();
				$('.menu-selector').css('top',position.top+'px').css('left',position.left+'px').addClass('transition');
				
			}
			
			function loading(){
				device();		
				user();
				user_table.fnReloadAjax();
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
					console.log(response);
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