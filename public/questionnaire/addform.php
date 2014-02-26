<?php

$formid=$_GET["formid"];

include("connect.php");
mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ; 

$sql='UPDATE `add_form` SET `name`="'.$formid.'" '; 
$select = mysql_query($sql, $connect) ;

?>
<html>
<head>
<title>Questionnaire</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script language="JavaScript" type="text/javascript" src="LnwForm_files/jquery-1.4.4.min.js"></script>
<script language="JavaScript" type="text/javascript" src="LnwForm_files/jquery-ui-1.8.7.custom.min.js"></script>

<link rel="stylesheet" type="text/css" href="./Form_files/formdata.css">

<link rel="stylesheet" type="text/css" href="./Form_files/y2013.css" />

</head><body class="lnwform-unit">

<table cellpadding="0" cellspacing="0" class="table_shadow" align="center" ">
	<thead>
		<tr>
			<td class="left"></td>
			<td class="center"></td>
			<td class="right"></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="left"></td>
			<td class="center">
				<div class="wrapper">
	<style type="text/css">
</style>
<div class="crown">
	
</div>
<div class="container">
	<div align="center" style="line-height:25px; font-size: 16px;padding: 100px 0px 50px;">
		<b>Successful !</b><br />
		
	
	</div>
	<div align="center" style="width: 100%;  margin: 0px; padding: 0px;">
		<div id="add_field" style="float: none; ">
			<ul class="button_form_design" style="float: none; margin: 0px; padding: 0px; ">
			   
			</ul>
		</div>
		<br />
		Powered by Questionnaire</div>
	</div>
</div>				</div>
			</td>
			<td class="right"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td class="left"></td>
			<td class="center"></td>
			<td class="right"></td>
		</tr>
	</tfoot>
</table>

</body>
</html>