<html>
	<body style="text-align:center;">
		<img id ="loading" src="/img/loading.gif" style="">
		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				$('#loading').fadeOut(function(){
					window.location.href ="<?php echo $auth_url;?>";
				});
			});
		</script>
	</body>
</html>
