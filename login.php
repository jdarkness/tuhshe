<?php
	$error=0;
	$msgerror="";
	if (isset($_POST['txtlogin_email'])){
		$txtlogin_email=($_POST['txtlogin_email']);
		//echo "segunda ves<br />";
		$mensaje="";
		require("include/funciones.php");
		$link=conectar_a_bd();
		$todo_bien='no';
		if (!empty($_POST['txtlogin_email'])) {
			$usuario=texto_seguro($_POST['txtlogin_email'], $link);
			$todo_bien='si';
		} else {
			$mensaje="por favor escriba su email";
			$todo_bien='no';
		}
		if (!empty($_POST['txtlogin_password'])) {
			$password_usuario=texto_seguro($_POST['txtlogin_password'],$link);
			$todo_bien='si';
		} else {
			$mensaje="por favor escriba su contraseña";
			$todo_bien='no';
		}
		//$link=conectar_a_bd('u94051_actividad');		
		$consulta="SELECT nombre, salt, contrasenia, id  FROM usuario WHERE correo='{$usuario}'";
		$resultado=consulta($consulta, $link);
		//var_dump($resultado);
		$reg_encontrados=mysqli_num_rows($resultado);
		if ($reg_encontrados>0) {
			/* array asociativo */
			$fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);			
			$salt=$fila["salt"];
			$password_bd=$fila["contrasenia"];
			//echo "<br><br>";
			$password_usuario=encriptar_password($password_usuario, $salt);
			if ($password_bd==$password_usuario) {				
				/****** creamos una sesion para poder entrar al area de actividades ******/				
				$_SESSION['nombre']=$fila["salt"];
				$_SESSION['login']=1;
				$_SESSION['id_usuario']=$fila["id"];
				$_SESSION['fecha_hoy']=date("Y-m-d");
				//print_r($_SESSION);				
				header("Location: actividades.php");
				exit();
			} else {
				$error=1;
				$msgerror="usuario o password incorrecto";
				//echo "usuario o password incorrecto1<br>";
				//echo "intentelo nuevamente";
			}
		} else {
			$error=1;
			$msgerror="usuario o password incorrecto";
			//echo "usuario o password incorrecto2<br>";
			//echo "intentelo nuevamente";
		}
		mysqli_free_result($result);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Tuhshe</title>
	<link rel="stylesheet" href="stylesheets/prelanzamiento.css" type="text/css" />
</head>
<body>
	<div id="marco">
		<h1 id="encabezado">Tuhshe, aumentando tu productividad</h1>
		<div id="menu">
			<ul>
				<li><a href="index.php">Inicio</a></li>
				<li>| <a href="pantallazos.php">Screenshots</a></li>
				<li>| Acceder</a></li>
				<li>| <a href="signup.php">Crear Cuenta</a></li>
			</ul>
		</div>
		<fieldset>
		<form id="form_login" action="<?php echo basename(__FILE__); ?>" method="post">
			<label for="txtlogin_email">Correo electronico: <input type="text" name="txtlogin_email" id="txtlogin_email" /></label>
			<label for="txtlogin_password">Contrase&ntilde;a: <input type="password" name="txtlogin_password" id="txtlogin_password"/></label>
			<?php
				if ($error==1) { ?>
			<label class="error"><?php echo $msgerror; ?></label>	
				<?php }
			?>			
			<input type="submit" name="btnlogin" id="btnlogin" value="Login" onclick="this.form.submit();"/>
		</form>
		</fieldset>
	</div>
</body>
</html>