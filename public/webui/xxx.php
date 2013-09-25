<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dashboard - FITM NETWORK MONITORv1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="./css/bootstrap.css" rel="stylesheet">
	
	

    <style>
      body {
        padding-top: 60px; 
		position:relative;
		height:100%;
		background:url(./img/bg.jpg);
      }
	   canvas{
        width: 100% !important;
        max-width: 400px;
        height: auto !important;
    }
	.bar {
		
		background-color:rgba(0,0,0,.1);
		-webkit-border-radius:25px;
		-moz-border-radius:25px;
		-ms-border-radius:25px;
		border-radius:20px;
		-webkit-box-shadow:0 1px 0 rgba(255,255,255,.03),inset 0 1px 0 rgba(0,0,0,.1);
		-moz-box-shadow:0 1px 0 rgba(255,255,255,.03),inset 0 1px 0 rgba(0,0,0,.1);
		-ms-box-shadow:0 1px 0 rgba(255,255,255,.03),inset 0 1px 0 rgba(0,0,0,.1);
		box-shadow:0 1px 0 rgba(255,255,255,.03),inset 0 1px 0 rgba(0,0,0,.1);
	}
	
	.bar.vertical {
		margin: auto;
		height:60px;
		width:10px;
		padding:3px;
	}
	.bar.horizontal {
		margin:auto;
		height:20px;
		
		padding:10px;
	}

	/* 
	This is the actual bar with stripes
	*/	
	.bar span {
		display:inline-block;
		height:100%;
		width:100%;
		-webkit-border-radius:20px;
		-moz-border-radius:20px;
		-ms-border-radius:20px;
		border-radius:20px;
		-webkit-box-sizing:border-box;
		-moz-box-sizing:border-box;
		-ms-box-sizing:border-box;
		box-sizing:border-box;
		overflow: hidden;
		-webkit-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
		-moz-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
		-ms-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
		box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
	}
	.bar span.move-down {
		-webkit-animation:move 2s linear infinite;
		-moz-animation:move 2s linear infinite;
		-ms-animation:move 2s linear infinite;
		animation:move 2s linear infinite;
	}
	.bar span.move-up {
		
		-webkit-animation:move-up 2s linear infinite;
		-moz-animation:move-up 2s linear infinite;
		-ms-animation:move-up 2s linear infinite;
		animation:move-up 2s linear infinite;
		
	}
	.bar span.warning{
		border:1px solid #ff9a1a;
		border-bottom-color:#ff6201;
		background-color:#d3d3d3;
		background-image:
			-webkit-linear-gradient(
			-45deg,
			rgba(255, 154, 26, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(255, 154, 26, 1) 50%,
			rgba(255, 154, 26, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-moz-linear-gradient(
			-45deg,
			rgba(255, 154, 26, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(255, 154, 26, 1) 50%,
			rgba(255, 154, 26, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-ms-linear-gradient(
			-45deg,
			rgba(255, 154, 26, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(255, 154, 26, 1) 50%,
			rgba(255, 154, 26, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			linear-gradient(
			-45deg,
			rgba(255, 154, 26, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(255, 154, 26, 1) 50%,
			rgba(255, 154, 26, 1) 75%,
			transparent 75%,
			transparent
		);
		-webkit-background-size:50px 50px;
		-moz-background-size:50px 50px;
		-ms-background-size:50px 50px;
		background-size:50px 50px;
	}
	.bar span.success{
		border:1px solid rgba(0, 182, 44, 1);
		border-bottom-color:rgba(0, 128, 31, 1);
		background-color:#d3d3d3;
		background-image:
			-webkit-linear-gradient(
			-45deg,
			rgba(0, 182, 44, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(0, 182, 44, 1) 50%,
			rgba(0, 182, 44, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-moz-linear-gradient(
			-45deg,
			rgba(0, 182, 44, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(0, 182, 44, 1) 50%,
			rgba(0, 182, 44, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-ms-linear-gradient(
			-45deg,
			rgba(0, 182, 44, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(0, 182, 44, 1) 50%,
			rgba(0, 182, 44, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			linear-gradient(
			-45deg,
			rgba(0, 182, 44, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(0, 182, 44, 1) 50%,
			rgba(0, 182, 44, 1) 75%,
			transparent 75%,
			transparent
		);
		-webkit-background-size:50px 50px;
		-moz-background-size:50px 50px;
		-ms-background-size:50px 50px;
		background-size:50px 50px;
	}
	
	.bar span.error{
		border:1px solid rgba(236, 51, 51, 1);
		border-bottom-color:rgba(207, 25, 25, 1);
		background-color:#d3d3d3;
		background-image:
			-webkit-linear-gradient(
			-45deg,
			rgba(236, 51, 51, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(236, 51, 51, 1) 50%,
			rgba(236, 51, 51, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-moz-linear-gradient(
			-45deg,
			rgba(236, 51, 51, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(236, 51, 51, 1) 50%,
			rgba(236, 51, 51, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-ms-linear-gradient(
			-45deg,
			rgba(236, 51, 51, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(236, 51, 51, 1) 50%,
			rgba(236, 51, 51, 1) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			linear-gradient(
			-45deg,
			rgba(236, 51, 51, 1) 25%,
			transparent 25%,
			transparent 50%,
			rgba(236, 51, 51, 1) 50%,
			rgba(236, 51, 51, 1) 75%,
			transparent 75%,
			transparent
		);
		-webkit-background-size:50px 50px;
		-moz-background-size:50px 50px;
		-ms-background-size:50px 50px;
		background-size:50px 50px;
	}
	
	.bar span.info{
		border:1px solid rgb(26, 163, 255);
		border-bottom-color:rgb(26, 145, 255);
		background-color:#d3d3d3;
		background-image:
			-webkit-linear-gradient(
			-45deg,
			rgb(26, 163, 255) 25%,
			transparent 25%,
			transparent 50%,
			rgb(26, 163, 255) 50%,
			rgb(26, 163, 255) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-moz-linear-gradient(
			-45deg,
			rgb(26, 163, 255) 25%,
			transparent 25%,
			transparent 50%,
			rgb(26, 163, 255) 50%,
			rgb(26, 163, 255) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			-ms-linear-gradient(
			-45deg,
			rgb(26, 163, 255) 25%,
			transparent 25%,
			transparent 50%,
			rgb(26, 163, 255) 50%,
			rgb(26, 163, 255) 75%,
			transparent 75%,
			transparent
		);
		background-image:
			linear-gradient(
			-45deg,
			rgb(26, 163, 255) 25%,
			transparent 25%,
			transparent 50%,
			rgb(26, 163, 255) 50%,
			rgb(26, 163, 255) 75%,
			transparent 75%,
			transparent
		);
		-webkit-background-size:50px 50px;
		-moz-background-size:50px 50px;
		-ms-background-size:50px 50px;
		background-size:50px 50px;
	}
	/*
	Animate the stripes
	*/	
	@-webkit-keyframes move{
	  0% {
		background-position: 0 0;
	  }
	  100% {
		background-position: 50px 50px;
	  }
	}	
	@-moz-keyframes move{
	  0% {
		background-position: 0 0;
	  }
	  100% {
		background-position: 50px 50px;
	  }
	}	
	@-ms-keyframes move{
	  0% {
		background-position: 0 0;
	  }
	  100% {
		background-position: 50px 50px;
	  }
	}	
	@keyframes move{
	  0% {
		background-position: 0 0;
	  }
	  100% {
		background-position: 50px 50px;
	  }
	}	
	
	/*           Move Up            */
	@-webkit-keyframes move-up{
	  100% {
		background-position: 0 0;
	  }
	  0% {
		background-position: 50px 50px;
	  }
	}	
	@-moz-keyframes move-up{
	  100% {
		background-position: 0 0;
	  }
	  0% {
		background-position: 50px 50px;
	  }
	}	
	@-ms-keyframes move-up{
	  100% {
		background-position: 0 0;
	  }
	  0% {
		background-position: 50px 50px;
	  }
	}	
	@keyframes move-up{
	  100% {
		background-position: 0 0;
	  }
	  0% {
		background-position: 50px 50px;
	  }
	}	
	.distribute{
		position:relative;
	}
	.distribute img{
		margin-top:-10px;
	}
	.distribute p{
		position:relative;
		top:-45px;
		color:white;
		margin:0px;
		padding:0px;
	}
	.row{
		margin:0px!important;
	}
	
	#core{ 
		position:relative;
		top:-8px;
		height:100px;
		margin-top:-1px;
		position:relative;
		background-color:rgb(170,170,170);
		border:solid rgb(100,100,100);
		border-width:1px 0px;
		background: rgb(242,246,248); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(242,246,248,1) 0%, rgba(216,225,231,1) 50%, rgba(181,198,208,1) 51%, rgba(224,239,249,1) 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(242,246,248,1)), color-stop(50%,rgba(216,225,231,1)), color-stop(51%,rgba(181,198,208,1)), color-stop(100%,rgba(224,239,249,1))); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%); /* IE10+ */
		background: linear-gradient(to bottom,  rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9',GradientType=0 ); /* IE6-9 */

		
	}
	#distribute{ position:relative;top:-23px;}
	#distribute2{ position:relative;top:-70px;}
    </style>
    
	
	<link href="./css/project.css" rel="stylesheet">
	<script src="./js/jquery.js"></script>
	<script src="./js/Chart.js"></script>
	<script src="./js/bootstrap.js"></script>
	
  </head>

  <body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">FITM Netmonv1</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Dashboard</a></li>
               <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Device
					<b class="caret"></b>
					</a>
					 <ul class="dropdown-menu">
						<li><a href="device.php?de_id=1">SW4503</a></li>
						<li><a href="device.php?de_id=2">R124</a></li>
						<li><a href="device.php?de_id=3">R101C</a></li>
						<li><a href="device.php?de_id=4">R330A</a></li>
						<li><a href="device.php?de_id=5">R401</a></li>
						<li><a href="device.php?de_id=6">R415</a></li>
						<li><a href="device.php?de_id=7">FITMFW5510</a></li>
					 </ul>
					
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Top 10 Ranking
					<b class="caret"></b>
					</a>
					 <ul class="dropdown-menu">
						<li><a href="topnet.php" >Network Ranking</a></li>
						<li><a href="servicerecv.php">Service Ranking</a></li>
						<li><a href="iprecv.php">IP Ranking</a></li>
					 </ul>
				</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
	
   
		<div class="row-fluid">
			<div class="span10 offset1 shadow">
				<div class="bgbold project_header">
					<div class="icon_novel">Network Overview</div>
				</div>
				<div class="" style="overflow:none;height:490px;background-color:#FFF;padding:20px 0px;">
					<div class="row-fluid">
						
						<div class="row-fluid" id="firewall">
							<div class="span12 text-center">
								<h4 class="text-error">FITMFW5510</h4>
								<img src="img/overview/fw.png" style="width:120px;height:auto;margin-bottom:-15px"></img>
								<br>
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-down text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="warning move-up text-center " ></span>
								</div>
							</div>
						</div>
						<div class="row-fluid" id="core">
							<div style="margin:15px;">
								<div class="span8" >
									<img src="img/overview/icon_log.png" style="width:50px;float:left;margin:15px;" ></img>
									<div>
										<h4>Network healtly is good.</h4>
										<p>No event Message</p>	
									</div>
								</div>
								<div class="span4">
									<h4 class="text-info">SW4503</h4>
								</div>
							</div>
							<img src="img/overview/router.png" style="width:130px;margin-top:20px;margin-left:-65px;;height:auto;position:absolute;left:50%;top:0;" ></img>
						</div>
						<div class="row" id="distribute">
							
							<div class="span3 text-center distribute">
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-down text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-up text-center " ></span>
								</div>
								<br>
								<img src="img/overview/router.png" style="width:130px;height:auto;" ></img>
								<p>R101C</p>
							</div>	
							<div class="span3 text-center distribute">
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-down text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-up text-center " ></span>
								</div>
								<br>
								<img src="img/overview/router.png" style="width:130px;height:auto;" ></img>
								<p>R330A</p>
							</div>
							<div class="span3 text-center distribute">
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-down text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-up text-center " ></span>
								</div>
								<br>
								<img src="img/overview/router.png" style="width:130px;height:auto;" ></img>
								<p>R401</p>
							</div>
							<div class="span3 text-center distribute">
							
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-down text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="success move-up text-center " ></span>
								</div>
								<br>
								<img src="img/overview/router.png" style="width:130px;height:auto;" ></img>
								<p>R415</p>
							</div>
						</div>
						<div class="row" id="distribute2">
							<div class="span3  text-center distribute">
								<div class="bar vertical" style="display:inline-block;">
									<span class="error text-center" ></span>
								</div>
								<div class="bar vertical" style="display:inline-block;">
									<span class="error text-center " ></span>
								</div>
								<br>
								<img src="img/overview/router.png" style="width:130px;height:auto;" ></img>
								<p>R124</p>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
	
		
	
  </body>
</html>
