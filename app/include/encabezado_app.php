	<?php
		$fecha_trabajo=date("Y-m-d");
		$hoy=date("j/M/Y");
	?>
	<div id="cabeza">
		<!-- <div id="titulo">Hola <i><?php echo $nombre; ?></i> tus actividades para hoy <i><?php echo texto_seguro($_SESSION['fecha_hoy'], $link); ?></i> son:</div> -->
		<div id="fecha">
			<div><span>Fecha:</span><i><a href="#" onclick="document.forms[0].fecha_trabajo.value='<?php echo $fecha_trabajo; ?>';document.forms[0].submit();"><?php echo $hoy; ?></a></i></div>
			<div><span>Hora:</span><i><?php echo date("H:i:s"); ?></i></div>
			<a id="salir" href="logout.php">Salir</a>
		</div>		
	</div>