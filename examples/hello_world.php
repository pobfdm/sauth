<?php
// Read "Prepare_database.md" for create the database
 
include_once("../sauth.php");
$myLogin = new Login();
 
$myLogin->DSN='sqlite:users.sqlite' ;
$myLogin->DBTABLE='users';
$myLogin->FIELDID='id';
$myLogin->FIELDUSER='user';
$myLogin->FIELDPASS='pass';
$myLogin->loginError='<p align="center" style="color: red">You are not a valid user!</p>';
 
if ($myLogin->LoginSession(true, true)==true) //LoginSession($form=true, $autoExit=true)
{
	echo '<p>Logged in, success! (<a href="logout.php">Logout</a>).</p>';
}else{
 	echo '<p>You are not logged in!</p>';
		 	//exit(1);	
}	 	
 
 
// If "$autoExit=true" the underlying html block is returned only in case of successful authentication.
echo'
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Login</title>
</head>
<body>
<br><br><br><br><div align="center"><b>If you are here you are logged in!</b><br><br>
<a href="logout.php" ><u>logout</u></a>
</div>
</body>
</html>';
 
?>
