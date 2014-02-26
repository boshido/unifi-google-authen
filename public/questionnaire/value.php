<?php

$qest_code 	= $_GET['quest_code'];
	$mac 		= $_GET['mac'];
	$ap 		= $_GET['ap'];
	$name 		= $_GET['name'];
	$fname 		= $_GET['fname'];
	$lname 		= $_GET['lname'];
	$google_id 	= $_GET['google_id'];
	$email 		= $_GET['email'];
	$auth_type 	= $_GET['auth_type'];

$url = '/guest/authorize?qest_code='.$quest_code.'&mac='.$mac.'&ap='.$ap.'&name='.$name.'&fname='.$fname.'&lname='.$lname.'&google_id='.$google_id.'&email='.$email.'&auth_type='.$auth_type;
		

setcookie("seturl",$url, time()+300);

include("connect.php");
mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ;

$addsql=" SELECT * FROM add_form  ";
$addresult = mysql_query($addsql);

while($row = mysql_fetch_array($addresult))
{
  
    $text = $row['name'];
    

}
$sql=" SELECT * FROM data$text WHERE email = '$email' ";
$result = mysql_query($sql);
$numrows = mysql_num_rows($result);



if($numrows == 0)
{

	echo '<script type="text/javascript">' . "\n"; 
	echo "window.location='/questionnaire/showform.php?formid=".$text."&email=".$email."';"; 
	echo '</script>'; 
}
else
{	
	echo '<script type="text/javascript">';
	echo 'window.location="'.$url.'";';
	echo '</script>';
	
	
}

	
?>

