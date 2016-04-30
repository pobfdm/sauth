<?php
session_start();
 
class Login {
 
 
//--Edit to fit your needs
var	$DSN =  'mysql:host=localhost;dbname=mydb' ;    
var	$DBUSER = '';
var $DBPASS = '';
 
//---Location credentials in db
var $DBTABLE   = 'mytable';					// name table containing users
var $FIELDUSER = 'user';					// name field containing the username
var $FIELDPASS = 'pass';					// name field containing the password (cripted with md5sum)
var $FIELDID   = 'id';						// name field containing the id
//---------------------------------
 
 

 
var $SESSIONUSER='php_auth_user' ;
var $SESSIONPASS='php_auth_pass' ;
var $SESSIONUSERID='php_auth_userid' ;


var $loginError;
var $headerloginform ;
var $loginform ;

 
function  __construct()
{
	if($this->loginError=='')
	 $this->loginError='<p style="color: red">Wrong password or username</p>';
	
	if($this->headerloginform=='')
	{
		$this->headerloginform='
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
				<html>
				<head>
				<title>Login</title>
				<style type="text/css">
					body {
						font-size: 0.9em;
						font-family: Geneva, Arial, Helvetica, sans-serif;
					}
					label
					{
						width: 4em;
						float: left;
						text-align: right;
						margin-right: 0.5em;
						display: block
					}
 
					.submit input
					{
						margin-left: 4.5em;
					}
					input
					{
						color: #535353;
						background: #FFE284;
						border: 1px solid #535353
					}
 
					.submit input
					{
						color: #000;
						background: #FFE284;
						border: 2px outset #d7b9c9
					}
					fieldset
					{
						border: 1px solid #FFA20C;
						width: 20em
					}
 
					legend
					{
						color: #fff;
						background: #FFa20c;
						border: 1px solid #535353;
						padding: 2px 6px
					} 
 
				</style>    
				</head>
				<body>';
		
		
	}
	
	
	
	if($this->loginform=='')
	{
		$this->loginform='<div align="center"><form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
				<fieldset>
				<legend>Login</legend>
						<p><label for="name">User</label><input id="user" type="text" name="user"></p>
						<p><label for="pass">Password</label><input id="pass" type="password" name="pass"></p>
						<p style="margin-left: 170px"><input id="login" type="submit" name="login" value="login"></p>
					</fieldset>
					</form>
					</div>
					</body>
					</html>	';
	}
}
 
function LoginSession($form=true, $autoExit=true)
{
 
	$AuthErrorMessage=$this->loginError;
	$rowcount=0;
 
	try
	{
		$dbh = new PDO($this->DSN, $this->DBUSER, $this->DBPASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
 
	catch (PDOException $myerror)
	{
		print "Database error: <br>" . $myerror->getMessage() . "<br/>";
	}
 
	@$sql="select * from ". $this->DBTABLE. " where ".$this->FIELDUSER."='".$_SESSION[$this->SESSIONUSER]. "' and ".$this->FIELDPASS."='".$_SESSION[$this->SESSIONPASS]."'";
	$stmt = $dbh->prepare($sql);
 
	//Controllo se la query è andata a buon fine
    if (! $stmt->execute() ) echo '<p>Error in query '.$sql.'</p>';
 
 
	//conto il numero di righe restituite dalla query
	while ($row = $stmt->fetch(PDO::FETCH_BOUND)) $rowcount++;
 
 
	if ($rowcount!=0)
	{
		//Logged in, success!
		$Error="";
		$dbh = null;	
		return true;
	}else{
		if (!isset($_POST["login"]) )
		{
			if ($form==true) @$this->MakeHtmlForm($Error);
		}else{
			$sql="select * from ".$this->DBTABLE. " where ".$this->FIELDUSER."='".$_POST["user"]. "' and ".$this->FIELDPASS."='".$_POST["pass"]."'";
			$stmt = $dbh->prepare($sql);
			if (! $stmt->execute() ) echo'<p>Error in query</p>';
 
			 $_SESSION[$this->SESSIONUSER]=$_POST["user"] ;
			 $_SESSION[$this->SESSIONPASS]=md5($_POST["pass"]);
 
			 $sql="select * from ". $this->DBTABLE. " where ".$this->FIELDUSER."='".$_POST["user"]. "' and ".$this->FIELDPASS."='".md5($_POST["pass"])."'";
			 $stmt = $dbh->prepare($sql);
			 if (! $stmt->execute() ) echo'<p>Error in query</p>';
 
			 //Prelevo lo userid
			 foreach ($dbh->query($sql) as $row)
			 {
				$_SESSION[$this->SESSIONUSERID]=$row[$this->FIELDID];
				continue;
			 }	
 
			 //Conto i record
			 $rowcount=0;
			 while ($row = $stmt->fetch(PDO::FETCH_BOUND)) $rowcount++;
 
			 if ($rowcount!=0)
			 {
				//Se sta qui e' autenticato, ed esce dalla funzione auth();
				$dbh = null;
				return true;
			 }else{
				// credenziali sbagliate
				$Error=$AuthErrorMessage;
				if ($form==true) $this->MakeHtmlForm($Error);
				$dbh = null;
				if ($autoExit==true) exit(1);
				return false;
			 }
		}
		//Non autenticato, cancella le variabili di sessione e esce.
		@session_start();
		@session_destroy();
		if ($autoExit==true) exit(1);
		return false ;
 
	}
 
}
 
function MakeHtmlForm($Error)
{
	//Render form for login
	echo $this->headerloginform;
	echo $Error;			
	echo $this->loginform;
}
 
 
}//fine classe
?>
