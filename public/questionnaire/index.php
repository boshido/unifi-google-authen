<?	
	session_start();
	$_SESSION["valid"] = 'no';
	$login=$_REQUEST["login"];
	if($login!=null)
	{			
				$username=$_REQUEST["username"];
                $password=$_REQUEST["password"];
				
				if($username==""||$password=="")
                {
						echo "คุณกรอกข้อมูลไม่ครบ";
						if($username=="")echo "<li>กรุณาใส่ Username</li>";
						if($password=="")echo "<li>กรุณาใส่ Password</li>";
				}
                else
                {
						/*mysql_connect("127.0.0.1","root","1234")or die("ไม่สามารถเชื่อมต่อได้");
						mysql_select_db("final")or die("ไม่สามรถถติดต่อฐานข้อมูลได้");
						$rs=mysql_query("select * from members where username='$username'");
						$ans=mysql_fetch_array($rs);
						$pass_db=$ans["password"];
                        */
                       
						include("connect.php");
						mysql_select_db($dbname , $connect);

                        $sql = "select * from checklogin where user_name = '$username' and pass = '$password'";
                        $result = mysql_query($sql);
                       	$numrows = mysql_num_rows($result);
                  		
                        	
                        
                        
						if($numrows == 1)
                        {
                        	
								$_SESSION["valid"] = 'yes';
								
						?>
								<script type="text/javascript">
                                    window.location = "Main.php";
								</script>
						<?
						}
						else
                        {
								$_SESSION["valid"] = 'no';
                                ?>
                            <!DOCTYPE html>
                            <!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
                            <!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
                            <!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
                            <!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
                            <head>
                            <meta charset="utf-8">
                            <title>Paper Stack</title>
                            <link rel="stylesheet" type="text/css" href="css/style.css" />
                            </head>
                            <body>
                            <div class="container">
	                            <section id="content">
		                            <form action="index.php"  method="POST">
			                            <h1>Questionnaire</h1>
			                            <div>
				                            <input type="text" placeholder="Username" required="" name="username" value="<?=$username?>"/>
			                            </div>
			                            <div>
				                            <input type="password" placeholder="Password" required="" name="password" />
			                            </div>
			                            <div >
				                            <center><input type="submit" value="Log in" name="login" /></center>
			                            </div>
                                        <div><center><FONT COLOR=red>* ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง</FONT></center></div>
		                            </form><!-- form -->
		
	                            </section><!-- content -->
                            </div><!-- container -->
                            </body>
                            </html>
                                <?php
						}
				}
	}
	else{
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Querstionnaire</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="container">
	<section id="content">
		<form action="index.php"  method="POST">
			<h1>Questionnaire</h1>
			<div>
				<input type="text" placeholder="Username" required  name="username" id="username" />
			</div>
			<div>
				<input type="password" placeholder="Password" required name="password"  id="password"/>
			</div>
			<div >
				<center><input type="submit" value="Log in" name="login" /></center>
				
			</div>
		</form><!-- form -->
		
	</section><!-- content -->
</div><!-- container -->
</body>
</html>
<?}?>