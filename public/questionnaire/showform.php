<?php
    
$formid=$_GET["formid"];
$data_authen = "pang_@hotmail.com";

include("connect.php");

if($connect)
{
    //echo "Database Connected.";
}
else
{
    echo "Database Connect Failed.";
}

mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ;  

$sql=" SELECT * FROM main_form WHERE form_id = $formid";

$select = mysql_query($sql, $connect) ;



while($row = mysql_fetch_array($select))
{
  
    $text2 = $row['form_text'];
    $text = html_entity_decode($text2);

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Questionnaire</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link rel="shortcut icon" href="Form_files/lnw_favicon.ico" />
<link rel="icon" href="Form_files/lnw_favicon.gif" type="image/gif"/>
<script language="JavaScript" type="text/javascript" src="Form_files/jquery-1.4.4.min.js"></script>
<script language="JavaScript" type="text/javascript" src="Form_files/jquery-ui-1.8.7.custom.min.js"></script>


<link rel="stylesheet" type="text/css" href="Form_files/showform.css">

<!--[if IE]>
	<style type="text/css">
		body { font-size: 11px; }
	</style>
<![endif]-->

<style type="text/css">
</style>
</head>

<body class="lnwform-unit">
	<table cellpadding="0" cellspacing="0" class="table_shadow" align="center" style="width: 300px;;">
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
             <form action='savedata.php' method='post' >
              <input type="hidden" value="<?=$formid?>"  name="form_id">
              <input type="hidden" value="<?=$data_authen?>"  name="email">   
             <? echo $text ;?>
             
             </form>
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
<script language="JavaScript" type="text/javascript">
function submit_form(form) {
	$.post('http://www.lnwform.com/form/save_data', $(form).serialize(), null, 'script');
	return false;
}
</script>
</body>
</html>