<?php
	//damos de baja al correo para el boletin
	
	// Obtenemos los valores para darlo de baja
	require("include/funciones.php");
	$link = conectar_a_bd('u94051_boletin');
	$email = texto_seguro($_GET['email'],$link);
	$hash = texto_seguro($_GET['codigo'],$link);		
	// ponemos el campo de activo a N en la base de datos	
	$fecha_baja = date("Y-m-d H:i:s");
	$consulta = "UPDATE email_boletin SET activo = 'NO', fechaBaja = '{$fecha_baja}' 
			    WHERE email = '{$email}' AND hash = '{$hash}'";
	$resultado = consulta($consulta,$link);
	// comprobar que se actualizo
	$baja = mysql_affected_rows();
	if ($baja >0) {
		$mensaje = "El email '{$email}' ha sido dado de baja, ";
	}
	else {
		$mensaje = "Hubo un error al darle de baja a su email '{$email}', si copio y pego el enlace, por favor hagalo de nuevo y verifique el codigo contenga 32 caracteres ";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Boletin</title>
	<link rel="stylesheet" href="stylesheets/prelanzamiento.css" type="text/css" />
	<meta http-equiv="refresh" content="10;url=index.php">
</head>
<body>
	<p><?php echo $mensaje; ?>, click <a href="index.php">aqui</a> para ir a la pagina principal</p>
</body>
</html>