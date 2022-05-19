<?php require 'web/include/doctype_web.php'; ?>
<?php require 'web/include/html_web.php'; ?>
<?php require 'web/include/head_web.php'; ?>
<body>
	<div id="marco">
		<?php require 'web/include/encabezado_web.php'; ?>
		<?php require 'web/include/menu_web.php'; ?>
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
			<p>Espera un poco y podras probar Tuhshe y darnos tu opinion</p>
			<h3>Alpha</h3>
			<p>Es sistema esta actualmente en desarrollo y esta en la etapa de pruebas antes de poder sacar una beta publica</p>
		</div>		
		<?php require 'web/include/pie_pagina_web.php'; ?>
	</div>
	<?php require 'web/include/analisis_web.php'; ?>
</body>
</html>