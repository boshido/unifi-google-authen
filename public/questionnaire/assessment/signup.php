<?
$link=mysql_connect("localhost","joeshow","1234");
mysql_select_db("querstion_android",$link);
mysql_query("SET NAME UTF8");
$username = $_POST['username'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];
if($username == "" && $password == ""){
	echo "Fail";
}
if($username != "" && $password == $repassword){
$sql = "INSERT INTO login (`username`,`password`) VALUES('$username','$password')";
$result = mysql_query($sql,$link);
if($result){
echo "OK";
}else{
echo "Fail";
}
}
else{
echo "Fail";
}
?>