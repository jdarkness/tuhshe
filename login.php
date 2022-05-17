<?php
	require 'app/include/funciones_app.php';
	$error=0;
	$msgerror="";
	if (isset($_POST['txtlogin_email'])){
		$txtlogin_email=($_POST['txtlogin_email']);
		echo "segunda ves<br />";
		$mensaje="";		
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
			$mensaje="por favor escriba su contrase�a";
			$todo_bien='no';
		}		
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
<?php require 'app/include/doctype_app.php'; ?>
<html class="no-js" lang="">
<?php require 'app/include/head_app.php'; ?>
<body>
	<div id="marco">
		<?php require 'app/include/encabezado_app.php'; ?>
		<?php require 'app/include/menu_app.php'; ?>
		<div id="contenido">
			<br>
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
		<?php require 'app/include/pie_pagina_app.php'; ?>
	</div>
	<?php require 'app/include/analisis_app.php'; ?>
</body>
</html>