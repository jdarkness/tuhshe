<?php
	require('include/funciones.php');
	$link=conectar_a_bd();
	$temp_anio=explode('-',texto_seguro($_SESSION['fecha_hoy'], $link));
	$anio=$temp_anio[0];
	$nombre=$_SESSION['nombre'];
	
	if (isset($_POST['anio']) && !empty($_POST['anio'])) {
		$anio=texto_seguro($_POST['anio'], $link);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="stylesheet" href="stylesheets/estilo.css" type="text/css" />
	<title>Calendario</title>
</head>
<body>
<div id="cuadro_pagina">
	<?php include 'include/encabezado.php'; ?>
	<?php include 'include/menu.php'; ?>
	<div id="calendario">
		<form name="frmfecha" id="frmfecha" action="actividades.php" method="post" >
		<input type="hidden" name="fecha_trabajo" value="" />
		</form>
		
		<form name="frmcalendario" id="frmcalendario" action="<?php echo basename(__FILE__); ?>" method="post" >
		<input type="hidden" name="anio" value="" />
		</form>
		<ul>
			<li>A&ntilde;o</li>
			<li><a href="#" onclick="document.forms[1].anio.value='<?php echo ($anio-1); ?>';document.forms[1].submit();"><<</a> <?php echo ($anio); ?> <a href="#" onclick="document.forms[1].anio.value='<?php echo ($anio+1); ?>';document.forms[1].submit();">>></a></li>
		</ul>
		<?php
			
			//$anio=2010;
			$meses_x_fila=4;
			$temp_filas=$meses_x_fila;
			echo '<div class="fila_mes">';
			for ($i=1; $i<=12; $i++) {
				echo calendario($i, $anio);
				if ($i==$temp_filas && $i<12) {	
					echo '</div>';
					echo '<div class="fila_mes">';					
					$temp_filas=$temp_filas+$meses_x_fila;
				}				
			}
			echo '</div>';
		?>
		
	</div>
</div>
</body>
</html> 