<?php
	error_reporting(E_ALL);
	//phpinfo();
	
	$usuario='usuario';
	$contrasenia='password';
	
	$host='localhost';
	$link = mysqli_connect($host, $usuario, $contrasenia );
	if (!$link) {
		die('Could not connect: ' . mysqli_error());
	}
	echo 'Connected successfully';
	/*mysql_close($link);	*/
	
	$db_selected = mysqli_select_db($basededatos, $link);
	if (!$db_selected) {
		die ('Can\'t use '.$basededatos.' : ' . mysqli_error());
	}
?>