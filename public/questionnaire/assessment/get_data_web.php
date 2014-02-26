<?php
header("content-type:text/javascript;charset=utf-8");  
$con=mysql_connect('localhost','joeshow','1234')or die(mysql_error());  // เปลี่ยน localhost เป็น ip ของ mysql server
mysql_select_db('querstion_android')or die(mysql_error());
mysql_query("SET NAMES UTF8");
$sql="SELECT * FROM form_web ORDER BY id DESC";
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	$output[]=$row;
}
print(json_encode($output));
mysql_close();
?>