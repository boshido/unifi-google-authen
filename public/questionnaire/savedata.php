<?php
$formid=$_POST["form_id"];


include("connect.php");
mysql_select_db(querstion_questionnaire);



 $sql = "SELECT `COLUMN_NAME` 
        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE `TABLE_SCHEMA`='querstion_questionnaire' AND `TABLE_NAME`='data$formid'";
 $select = mysql_query($sql, $connect) ;

 $str = "INSERT INTO data$formid (";
 $i=0;
while($row = mysql_fetch_array($select))
{
    foreach($row as $key =>$data){}
    if($data != 'dataID')
    {
        if($i!=0) $str .=",";
        $str .= $data;
++$i;
    }
}
$str .=") VALUES (";
$j=0;
 $select = mysql_query($sql, $connect) ;
while($row = mysql_fetch_array($select))
{
    foreach($row as $key =>$data){}
    if($data != 'dataID')
    {
        if($j!=0) $str .=",";
        $str .= "\"$_POST[$data]\"";
           ++$j;
          // echo "val".$_POST[$data]."<br>";
    }
}
$str .=")";


 $sql_save = $str ;
 mysql_select_db($dbname, $connect);
 mysql_query("SET NAMES UTF8") ;

 if (mysql_query($sql_save, $connect))
 {  
     $message ="ส่งข้อมูลเรียบร้อยแล้ว" ;
       
 }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
		<b><? echo $message ;?></b><br />
		ขอขอบคุณสำหรับการกรอกข้อมูลฟอร์ม <br />
	
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
