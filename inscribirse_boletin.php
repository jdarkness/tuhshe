<?php
	require("include/funciones.php");
	$link = conectar_a_bd('u94051_boletin');
	$email = texto_seguro($_POST['email'], $link);
	//echo $email;
	//echo $fecha = date("Y-m-d H:i:s");
	if (!validEmail($email)) {
		echo $mensaje = "Correo electronico invalido, por favor intentelo de nuevo";
	}
	else {
		// correo electronico valido, lo ingresamos a la base de datos
		// verificamos que no este dado de alta ya en el boletin		
		$consulta = "SELECT email FROM email_boletin WHERE email = '$email'";
		$resultado = consulta($consulta,$link);
		$num_row = mysql_num_rows($resultado);
		if ($num_row>0) {
			$mensaje =  'El Email ya fue dado de alta';
			$evento = -1;
		}
		else {
			// el email no esta, lo agregamos a la base de datos.
			$fecha_hoy = date("Y-m-d H:i:s");
			$hash = md5($fecha_hoy);
			$consulta = "INSERT INTO email_boletin (fechaAlta, hash, email ) 
			              VALUES ('{$fecha_hoy}', '{$hash}', '{$email}')";
			$resultado = consulta($consulta,$link);
			// comprobar que se inserto
			$se_agrego = mysql_affected_rows();			
			if ($se_agrego>0) {
				$mensaje="Gracias por inscribirse al boletin de Tuhshe.com";
				$evento = 2;
				$salto_linea = "\r\n";
				// enviar mail de agradecimiento
				// http://mx.php.net/manual/en/ref.mail.php  ** usuarios windows leerlo
				// http://glob.com.au/sendmail//
				// http://mx.php.net/manual/en/function.mail.php
				$to      = $email;
				$subject = 'Inscripcion Boletin Tuhshe.com';
				$message = 'Gracias por inscribirse al boletin de Tuhshe.com'.$salto_linea.$salto_linea;
				$message .= 'Si deseas darte de baja o si no te inscribiste y crees que es un error, por favor da click en el siguiente enlace'.$salto_linea.$salto_linea;
				$message .= 'http://www.tuhshe.com/baja_boletin.php?email='.$email.'&codigo='.$hash.$salto_linea.$salto_linea;
				$message .= 'si el enlace no funciona copia y pegalo en una nueva pestaña de tu navegador'.$salto_linea;
				$headers = 'From: boletin@tuhshe.com' .$salto_linea.
						   'No-Reply: boletin@tuhshe.com' . $salto_linea;;						   
				mail($to, $subject, $message, $headers);
				$enter="<br />";
				//$mensaje .= $enter .$to.$enter.$subject.$enter.$message;
				//echo $mensaje;
			}
			else {
				$mensaje = "Lo sentimos hubo un error al momento de darle de alta, por favor intentelo de nuevo";
				$evento = 1;
			}			
		}
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