<!DOCTYPE html>
<html>
	<head>
		<title>Loading User infomation</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="/css/overide.css" rel="stylesheet" media="screen">
	</head>
	<body style="text-align:center;">
		<img id ="loading" src="/img/loading.gif" style="">
		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				window.setTimeout(function() {
					$('#loading').fadeOut(function(){
						window.location.href ="<?php echo $auth_url;?>";
					});
				}, 2000 );
			});
		</script>
	</body>
</html>
