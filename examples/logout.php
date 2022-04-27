<?php
//le sole istruzioni necessarie per distruggere la sessione
session_start();
session_destroy();
 
//l'html che vogliamo compaia dopo il logout.
echo'
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Login</title>
</head>
<body>
<br><br><br><br><div align="center">Logout effettuato correttamente 
<a href="hello_world.php" ><u>fai di nuovo il login</u></a></div>
</body>
</html>';

?>
