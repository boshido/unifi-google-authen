<?php
header("content-type:text/javascript;charset=utf-8");  
$con=mysql_connect('localhost','joeshow','1234')or die(mysql_error());   // เปลี่ยน localhost เป็น ip ของ mysql server
mysql_select_db('querstion_android')or die(mysql_error());
mysql_query("SET NAMES UTF8");
if (isset($_POST)){
	if($_POST['isAdd']=='true'){
		$message=$_POST['hn'];
		$name = $_POST['MemberName'];
		$date = date('Y-m-d');
		$time = date('H:i:s');
		
		$sql="INSERT INTO `form_nat` (`name`,`message`,`date_serv`,`time`) VALUES ('$name','$message','$date','$time')";
		mysql_query($sql);
	}
}
mysql_close();
?>