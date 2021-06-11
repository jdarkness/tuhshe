<?php	
	require("include/funciones.php");
	//print_r($_SESSION);
	//print_r($_POST);
	$error=0;
	$msgerror="";
	if (!empty($_SESSION['nombre']) && !empty($_SESSION['login'])) {	
	$link=conectar_a_bd();
	/*echo $_SESSION['nombre']."<br>";
	echo $_SESSION['login']."<br>";*/
	$nombre=$_SESSION['nombre'];
	
	
	
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/estilo.css" type="text/css" />
		<title>Actividades</title>
	</head>
	<body >
	<div id="cuadro_pagina">
	<?php include 'include/encabezado.php'; ?>
	<?php include 'include/menu.php'; ?>
	<div id="cuadro_perfil">
	<fieldset>
			<legend><b>Datos del Perfil</b></legend>
			<form action="<?php echo basename(__FILE__); ?>" method="post">
				<label for="txtSignupEmail" ><span class="texto_input">Correo electronico<span class="requerido">*</span> :</span><input type="text" name="txtSignupEmail" id="txtSignupEmail" /></label>
				<label for="txtSignupNombres"><span class="texto_input">Nombre (s) :</span><input type="text" name="txtSignupNombres" id="txtSignupNombres" /></label>
				<label for="txtSignupApellidos"><span class="texto_input">Apellido (s) :</span><input type="text" name="txtSignupApellidos" id="txtSignupApellidos" /></label>
				<label>
				<span class="texto_input"></span>
					<input type="submit" name="btnSignup" id="btnSignup" value="Guardar Cambios" onclick="this.form.submit();"/>
				</label>
				<label for="txtActualPassword"><span class="texto_input">Contrase&ntilde;a Actual<span class="requerido">*</span> :</span><input type="password" name="txtSignupPassword" id="txtSignupPassword"/></label>
				<label for="txtNewPassword"><span class="texto_input">Contrase&ntilde;a Nueva<span class="requerido">*</span> :</span><input type="password" name="txtSignupPassword" id="txtSignupPassword"/></label>
				<label for="txtRetryNewPassword"><span class="texto_input">Repetir Contrase&ntilde;a Nueva<span class="requerido">*</span> :</span><input type="password" name="txtSignupRetryPassword" id="txtSignupRetryPassword"/></label>
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
				<label>
				<span class="texto_input"></span>
					<input type="submit" name="btnSignup" id="btnSignup" value="Cambiar Contrase&ntilde;a" onclick="this.form.submit();"/>
				</label>
				<label for=""><span class="requerido">*</span>Campos requeridos</label>
			</form>
		</fieldset>
	<div
	</div>
	</body>
</html>
<?php
	
	mysqli_free_result($consulta);
	mysqli_close($link);
} else { ?>
	<h3>Para poder ver sus actividades debes ingresar a tu cuenta, click <a href="index.php">aqui</a> para ingresar</h3>
	<meta http-equiv="refresh" content="2;index.php">
<?php
} // cierre del else
?>