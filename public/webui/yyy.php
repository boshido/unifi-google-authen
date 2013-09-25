
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Top 10 IP Destination (Recieve) Traffic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
	

    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		position:relative;
		height:100%;
		background:url(./img/bg.jpg);
      }
	   canvas{
        width: 100% !important;
        max-width: 1000px;
        height: auto !important;
	 }

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
              <li><a href="index.php">Dashboard</a></li>
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

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span1"></div>
			<div class="span10 shadow">
				<div class="bgbold project_header">
					<div class="icon_novel">Top 10 IP Destination Total Usage ( Last 1 Hour )</div>
				</div>
				<div class="project_list_panel" style="overflow:none;height:900px;">
					<div class="media " style="height:900px;" >
						
						<div class="span12">
						<br>
						<center>
							
							<br><br>
							<canvas id="canvas" height="300" width="1000"></canvas>
							<br><br><br>
							<div class="span2"></div>
							<div class="span8">
								<table border=1 class="table table-bordered">
									<caption><h3>Top 10 IP Destination Total Usage ( Last 1 Hour )</h3></caption>
									<thead>
										<tr>
											<th style="width:5%;text-align:center;">#</th>
											<th style="width:35%px;text-align:center;">Username/Computer Name</th>
											<th style="width:20%px;text-align:center;">IP Address</th>
											<th style="width:20%px;text-align:center;">Total Usage</th>
											<th style="width:20%px;text-align:center;">Current Usage</th>
										</tr>
									</thead>
									<tbody>
									<?php
										/*$array_netid[$array_slot] = ip2net($array_many_join['di_ipaddr'],$array_many_join['di_subnet']);
										$array_vlanid[$array_slot] = $array_many_join['dv_vlanid'];
										$array_vlanname[$array_slot] = $array_many_join['dv_vlanname'];
										$array_in_ocet[$array_slot] = round(($array_many_join['df_avgin']/5)/60);
										$array_out_ocet[$array_slot] = round(($array_many_join['df_avgout']/5)/60);
										$array_label[$array_slot] = $array_many_join['dv_vlanname'] ;*/
										for($i=0;$i<count($array_totalocet);$i++)
										{
											echo '<tr>';
											echo '<td style="text-align:right;">'.($i+1).'</td>';
											echo '<td style="text-align:center;">No API Provide</td>';
											//echo '<td style="text-align:center;">'.$array_vlanid[$i].'</td>';
											echo '<td style="text-align:center;">'.$array_ip[$i].'</td>';
											echo '<td style="text-align:right;">'.$array_totalocet[$i].' '.$var_scale_text.'</td>';
											echo '<td style="text-align:right;">'.$array_currentocet[$i].' '.$var_scale_text2.'</td>';
											echo '</tr>';
										}
									?>
									</tbody>
								</table>
							</div>
							<div class="span2"></div>
						</center>



						</div>
					</div>

					
					
					</div>
					<div class="bgbold btn_project_home" style="height:50px;"></div>
					<br><br><br>
				</div>
			</div>
		</div>
	</div>

	<script>
		var data = {
					labels : <?php echo json_encode($array_ip); ?>,
					datasets : [
						{
							fillColor : "rgba(255,0,0,0.5)",
							strokeColor : "rgba(255,0,0,1)",
							data : <?php echo json_encode($array_totalocet); ?>
						}
					]
				};
		
			var barOption = {
				scaleShowLabels : true,
				scaleLabel : "<%=value%> <?php echo $var_scale_text;?>",
				scaleShowGridLines : true,
				scaleGridLineColor : "rgba(0,0,0,.20)",
				scaleGridLineWidth : 1,
				scaleFontColor : "#000",
				};

		var myBar = new Chart(document.getElementById("canvas").getContext("2d")).Bar(data,barOption);
		
	
	</script>
</body>
</html>