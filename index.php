<?php
	require("include/funciones.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Prelanzamiento</title>
	<link rel="stylesheet" href="stylesheets/prelanzamiento.css" type="text/css" />
</head>
<body>
	<div id="marco">
		<h1 id="encabezado">Tuhshe <span>[ Tomando al mundo con tranquilidad ]</span></h1>
		<ul id="menu">
			<li>Inicio</li>
			<li>| <a href="pantallazos.php">Screenshots</a></li>
			<li>| <a href="login.php">Acceder</a></li>
			<li>| <a href="signup.php">Crear Cuenta</a></li>
		</ul>
		<div id="contenido">
			<p>
				Pronto sera el lanzamiento de Tuhshe, un sistema para organizar tus actividades diarias concentrandote solamente en aquellas que son mas importantes.
			</p>
			<p>
				Podras aumentar tu productividad definiendo tus actividades mas importantes del dia para que de esa manera avances en tu vida dia a dia.
			</p>
			<p>
				Mantente al tanto visitando regularmente la pagina, para que te enteres de los avances y actualizaciones				
			</p>
			<p>
				Estare en contacto muy pronto.
			</p>
		</div>
		<div id="barra_lateral">			
			<h3>Boletin</h3>
			<p>Inscribete al boletin y mantente informado sobre tuhshe.com				
				<form action="inscribirse_boletin.php" method="POST">
					<input type="text" name="email" id="email" class="texto">
					<input type="submit" name="enviar" id="btnenviar" value="Inscribise" class="boton" >
				</form>
			</p>
			<h3>Prelanzamiento</h3>
			<p>Espera un poco y podras probar Tuhshe y podras dar tu opinion</p>
			<h3>Alpha</h3>
			<p>Es sistema esta actualmente en desarrollo y esta en la etapa de pruebas antes de poder sacar una beta publica</p>
		</div>		
		<div id="pie_pagina">
			<ul>
				<li>Tuhshe.com</li>
			<ul>
		</div>
	</div>
</body>
</html>