<?php
	$error=0;
	$msgerror="";
	if (isset($_POST['txtSignupEmail'])){
		//echo "segunda ves<br />";
		// Inicializamos variables
		require("include/funciones.php");
		$link=conectar_a_bd();
		
		$mensaje="";		
		$todo_bien='no';
		
		// Recibimos los datos del formulario
		$txtSignupEmail=texto_seguro($_POST['txtSignupEmail'],$link);		
		$txtSignupNombres=texto_seguro($_POST['txtSignupNombres'],$link);
		$txtSignupApellidos=texto_seguro($_POST['txtSignupApellidos'],$link);
		$txtSignupPassword=texto_seguro($_POST['txtSignupPassword'],$link);
		$txtSignupRetryPassword=texto_seguro($_POST['txtSignupRetryPassword'],$link);
		
		// Realizamos las comprobaciones 
		
		/* Primero que los campos obligatorios tengan algo */ 
		if (strlen($txtSignupEmail)<=0 || strlen($txtSignupPassword)<=0 || strlen($txtSignupRetryPassword)<=0) {
			$error = 1;
			$msgerror ="Llena los campos obligatorios";
		} else {
			// Los campos obligatorios estan llenos
			// Revisamos que sea un email valido
			if (!validEmail($txtSignupEmail)) {
				// Correo invalido
				$error = 1;
				$msgerror ="Email no valido";
			} else {
				// revisamos que las dos contrase�as sean iguales
				if ($txtSignupPassword != $txtSignupRetryPassword) {
					$error = 1;
					$msgerror ="Las constrase&ntilde;as no coinciden";					
				}
				else {
					// Todo bien, revisamos que el correo no este dado de alta.
					$consulta="SELECT correo FROM usuario WHERE correo='{$txtSignupEmail}'";
					$resultado=consulta($consulta, $link);
					if (mysqli_num_rows($resultado)>0) {
						$error = 1;
						$msgerror ="Usuario ya existe";
					} else {
						// Todo en orden procesemos a crear al nuevo usuario
						$nombre = "$txtSignupNombres $txtSignupApellidos";
						$salt = crear_salt();
						$contrasenia = encriptar_password($txtSignupPassword, $salt);
						$correo =  $txtSignupEmail;
						//tabla id 	nombre 	contrasenia 	correo 	salt
						$consulta="INSERT INTO usuario(nombre, contrasenia, correo, salt) 
								   VALUES('{$nombre}', '{$contrasenia}','{$correo}','{$salt}')";
						$nuevo_usuario=consulta($consulta, $link);
						//echo mysqli_affected_rows($link);
						if (mysqli_affected_rows($link)) {
							$error=2;
							$msgerror="El usuario se agrego correctamente";
						} else {
							$error=1;
							$msgerror="Usuario no creado, intentelo de nuevo";
						}
					}
				}				
			}			
		}
		
		/*
		alguno@alguno.com::alguno
		alguno2@alguno.com::alguno2
		alguno3@alguno.com::alguno
		alguno4@alguno.com::alguno
		alguno5@alguno.com::alguno
		*/
	}
?>
<?php require 'include/doctype.php'; ?>
<html class="no-js" lang="">
<?php require 'include/head.php'; ?>
<body>	
	<div id="marco">
		<?php require 'include/encabezado_web.php'; ?>
		<?php require 'include/menu_web.php'; ?>
		<div id="contenido">
		<fieldset>
			<legend><b>Datos de la Cuenta</b></legend>
			<form id="form_login" action="<?php echo basename(__FILE__); ?>" method="post">			
				<label for="txtSignupEmail" ><span class="texto_input">Correo electronico<span class="requerido">*</span> :</span><input type="text" name="txtSignupEmail" id="txtSignupEmail" /></label>
				<label for="txtSignupNombres"><span class="texto_input">Nombre (s) :</span><input type="text" name="txtSignupNombres" id="txtSignupNombres" /></label>
				<label for="txtSignupApellidos"><span class="texto_input">Apellido (s) :</span><input type="text" name="txtSignupApellidos" id="txtSignupApellidos" /></label>
				<label for="txtSignupPassword"><span class="texto_input">Contrase&ntilde;a<span class="requerido">*</span> :</span><input type="password" name="txtSignupPassword" id="txtSignupPassword"/></label>
				<label for="txtSignupRetryPassword"><span class="texto_input">Repetir Contrase&ntilde;a<span class="requerido">*</span> :</span><input type="password" name="txtSignupRetryPassword" id="txtSignupRetryPassword"/></label>
				<?php
					if ($error>0 ) { 
						switch ($error)
						{
							case 1:
							$class="error";
							break;
							case 2:
							$class="exito";
							break;
						}
					?>
				<label class="<?php echo $class; ?>"><?php echo $msgerror; ?></label>	
					<?php }
				?>			
				<input type="submit" name="btnSignup" id="btnSignup" value="Crear Cuenta" onclick="this.form.submit();"/>
				<label for=""><span class="requerido">*</span> Campos requeridos</label>
			</form>
		</fieldset>
	</div>
	<?php require 'include/pie_pagina.php'; ?>
	</div>
	<?php require 'include/analisis.php'; ?>
</body>
</html>
<?php
	mysqli_free_result($result);
?>