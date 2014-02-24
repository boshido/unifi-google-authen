<?php

include("connect.php");
mysql_select_db($dbname , $connect);
mysql_query("SET NAMES UTF8") ;  

$sql = "select * from checklogin where user_name = 'admin' and pass = '1234'";
$select = mysql_query($sql, $connect) ;
$row = mysql_fetch_array($select);
 
     
     


 $text2 = $row['user_name'];
   
          

 

  echo $text2 ;
 
?>

