<?
	$objConnect = mysql_connect("localhost","joeshow","1234");
	$objDB = mysql_select_db("querstion_android");

	$strUsername = $_POST["strUser"];
	$strPassword = $_POST["strPass"];
	$strSQL = "SELECT * FROM login WHERE username = '$strUsername' AND password = '$strPassword' ";
	

	$objQuery = mysql_query($strSQL);
	$objResult = mysql_fetch_array($objQuery);
	$intNumRows = mysql_num_rows($objQuery);
	if($intNumRows==0)
	{
		$arr['StatusID'] = "0"; 
		$arr['MemberID'] = "0"; 
		$arr['Error'] = "Incorrect Username and Password";	
	}
	else
	{
		$arr['StatusID'] = "1"; 
		$arr['MemberID'] = $objResult["username"]; 
		$arr['Error'] = "";	
	}

	
	mysql_close($objConnect);
	
	echo json_encode($arr);
?>
