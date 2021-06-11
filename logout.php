<?php
	session_start();
	$_SESSION = array(); 
	session_destroy(); 
?><meta http-equiv="refresh" content="1;url=index.php">