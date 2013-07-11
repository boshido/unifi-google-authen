<html>
	<head>
		<title>FITM Wifi Authorization</title>
		
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
	</head>
	<body>
	<div class="menu">
		<div class="menu-content">
			<div class="banner">FITM</div>
			<div class="sign-out"><div class="sign-out-btn" >Sign Out</div></div>
			<div class="timer"><span>Time Remain :</span>11:00:00</div>
		</div>	
	</div>	
	<div class="pure-g cover" >
		<div class="pure-u-1">
			<?php //print_r($user); ?>
			<img class="cover-img" alt="Cover photo" src="https://lh6.googleusercontent.com/-0J9hpa4mLeM/UbMLzcWV0jI/AAAAAAAAAMQ/v9em9XOWv6g/s1907-fcrop64=1,00000000ffffadab/rod-luff-fantasy-painting-art-bird-nest-head-nature-elf-beautiful.jpg">
		</div>
		<div class="profile-bar">
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
	</div>
	</body>
</html>