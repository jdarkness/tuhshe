<?php	
	require("include/funciones.php");
	//print_r($_SESSION);
	//print_r($_POST);
	$error=0;
	$msg_error="";
	if (!empty($_SESSION['nombre']) && !empty($_SESSION['login'])) {	
	$link=conectar_a_bd();
	/*echo $_SESSION['nombre']."<br>";
	echo $_SESSION['login']."<br>";*/
	$nombre=$_SESSION['nombre'];
	//$_SESSION['fecha_hoy']=date("Y-m-d");	
	$accion="";
	$no_actividad="";
	
	/*** vemos si venimos de calendario para asignar otra fecha de trabajo que no sea hoy ***/
	if (isset($_POST['fecha_trabajo']) && !empty($_POST['fecha_trabajo'])) {
		$_SESSION['fecha_hoy']=texto_seguro($_POST['fecha_trabajo'],$link);
	}
	
	
	/****** codigo para insertar nuevas actividades ******/
	if (isset($_POST['nueva_actividad']) && !empty($_POST['nueva_actividad'])) {
		/****** agregamos la actividad a la base de datos ******/
		$consulta="INSERT INTO actividad(id_usuario, fecha, actividad) 
				   VALUES('".texto_seguro($_SESSION['id_usuario'], $link)."','".texto_seguro($_SESSION['fecha_hoy'], $link)."','".texto_seguro($_POST['nueva_actividad'], $link)."')";
		$insertar_actividad=consulta($consulta, $link);		
		$reg_agregados=mysqli_affected_rows($link);
		if ($reg_agregados<0) {
			/****** no se agrego a la tabla la activida ******/
			echo "no se pudo agregar la actividad por favor intentelo de nuevo";
		}
		//mysql_free_result($insertar_actividad);
	}
	
	
	/****** codigo para las modificaciones y borrado de las actividades ******/
	if (isset($_POST['no_actividad']) && !empty($_POST['no_actividad']) && isset($_POST['accion']) && !empty($_POST['accion']) ) {
		/****** procesamos una actualizacion o un borrado de una actividad ******/
		$no_actividad=texto_seguro($_POST['no_actividad'], $link);
		$accion=texto_seguro($_POST['accion'], $link); // 2 borramos la actividad y 3 la actualizamos y 4 ponemos una nota a la actividad
		
		//echo "no_actividad:$no_actividad && accion:$accion<br />";
		
		if ($accion == 2) {
			// Borramos una actividad
			
			// Buscamos que no tenga alguna nota del dia, si es asi se elimina
			$consulta="SELECT id FROM notas WHERE id_actividad='$no_actividad'";
			$resultado=consulta($consulta, $link);
			$num_notas=mysqli_num_rows($resultado);
			if ($num_notas>0) {
				// Hay nota del dia y se borrara
				// $fila = mysqli_fetch_array($consulta_actividad, MYSQLI_ASSOC);
				$fila=mysqli_fetch_array($resultado, MYSQLI_ASSOC);
				$consulta="DELETE FROM notas WHERE id=".$fila["id"];
				$resultado=consulta($consulta, $link);
				$reg_borrados_nota=mysqli_affected_rows($link);
				if ($reg_borrados_nota<=0) {
					$error=1;
					$msg_error="No se borro la nota del dia de la actividad, intentelo nuevamente";
				}
			}
			// borramos la actividad
			$consulta="DELETE FROM actividad WHERE id='$no_actividad' AND id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'";
			$borrar_actividad=consulta($consulta, $link);
			$reg_borrados=mysqli_affected_rows($link);
			if ($reg_borrados<=0) {
				$error=1;
				$msg_error="no se pudo borrar la actividad por favor intentalo otra vez";
			}
		}		
		
		if ($accion == 3 ) {
			/****** buscamos la actividad que se va a modificar ******/
			$actividad="";
			$consulta="SELECT actividad FROM actividad WHERE id='$no_actividad' AND id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'";
			$consulta_actividad=consulta($consulta, $link);
			$reg_actividad=mysqli_num_rows($consulta_actividad);
			if ($reg_actividad>0) {
				$fila = mysqli_fetch_array($consulta_actividad, MYSQLI_ASSOC);
				$actividad = $fila["actividad"];
				if (!isset($_POST['modificar_actividad']) && empty($_POST['modificar_actividad'])) {
					$_SESSION['modificar_actividad']=$no_actividad;
				}
			} else {
				echo "no se encontro la actividad, por favor intente actualizar la pagina";
			}
			//echo "<b>".$_SESSION['modificar_actividad']."</b>";
			
			if (isset($_POST['modificar_actividad']) && !empty($_POST['modificar_actividad']) && $_SESSION['modificar_actividad']==$no_actividad) {
			/****** actualizamos la actividad ******/
				$consulta="UPDATE actividad SET actividad='".texto_seguro($_POST['modificar_actividad'], $link)."' 
						   WHERE id='$no_actividad' AND id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'";
				$actualizar_actividad=consulta($consulta, $link);
				$reg_actualizados=mysqli_affected_rows($link);
				$_SESSION['modificar_actividad']="";
				$no_actividad="";
				$accion="";
				if ($reg_actualizados<=0) {
					echo "no ser pudo actualizar la actividad por favor intentelo otra vez ";
				}
			} 
			
		}
		
		if ($accion == 6 ) {
			/****** buscamos la actividad que se va a marcar como hecha ******/
			//echo "estoy en el if 6";
			$actividad='';
			$completada='';
			$consulta="SELECT actividad, completada FROM actividad WHERE id='$no_actividad' AND id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'";
			$consulta_actividad=consulta($consulta, $link);
			$reg_actividad=mysqli_num_rows($consulta_actividad);
			if ($reg_actividad>0) {
				//echo "<br>encontre la actividad";
				$fila = mysqli_fetch_array($consulta_actividad, MYSQLI_ASSOC);
				$actividad = $fila["actividad"];
				$completada = $fila["completada"];
				switch ($completada) {
					case 0:
						$completada=1;
						break;
					case 1:
						$completada=0;
						break;
				}
				if (!isset($_POST['modificar_actividad']) && empty($_POST['modificar_actividad'])) {
					$_SESSION['modificar_actividad']=$no_actividad;
				}
			} else {
				echo "no se encontro la actividad, por favor intente actualizar la pagina";
			}
			//echo "<b>".$_SESSION['modificar_actividad']."</b>";			
			if ($_SESSION['modificar_actividad']==$no_actividad) {
			/****** actualizamos la actividad ******/
				//echo "<br> voy a modificar la actividad";
				$consulta="UPDATE actividad SET completada='$completada' 
						   WHERE id='$no_actividad' AND id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'";
				$actualizar_actividad=consulta($consulta, $link);
				$reg_actualizados=mysqli_affected_rows($link);
				$_SESSION['modificar_actividad']="";
				$no_actividad="";
				$accion="";
				if ($reg_actualizados<=0) {
					echo "no se pudo marcar como completada la actividad por favor intentelo otra vez ";
				}
			} 
			
		}
		
	}
	
	/****** codigo para dar de alta las notas ******/
	//if (isset($_POST['nota']) AND !empty($_POST['nota']) AND isset($_POST['accion']) AND !empty($_POST['accion'])) {
	if (isset($_POST['nota']) AND isset($_POST['accion']) AND !empty($_POST['accion'])) {
		$accion=texto_seguro($_POST['accion'], $link);
		$nota=texto_seguro($_POST['nota'], $link);
		//echo "accion::{$accion} ## nota::{$nota} <br/>";
		//echo "<br>'".isset($_POST['no_nota'])."'<br>";
		if ($accion==4 && empty($_POST['no_nota']) && empty($no_actividad)) {
			/****** insertamos la nota a la base de datos ******/
			/****** primero la actividad ******/
			//echo $_POST['no_actividad']."::<br>";
			$consulta="INSERT INTO actividad(id_usuario, fecha, actividad, completada)
					   VALUES('".texto_seguro($_SESSION['id_usuario'], $link)."', '".texto_seguro($_SESSION['fecha_hoy'], $link)."','', '1')";
			$insertar_nota_dia=consulta($consulta, $link);
			$se_agrego=mysqli_affected_rows($link);
			/****** considerar eso de transacciones, buscar como se hace en mysql ******/
			if ($se_agrego>0) {
				$id_actividad=mysqli_insert_id($link);
				$consulta="INSERT INTO notas(id_actividad, nota, tipo_nota)
						   VALUES('$id_actividad', '$nota', 'ND')";
				$insertar_nota_dia=consulta($consulta, $link);
				$se_agrego=mysqli_affected_rows($link);
				if ($se_agrego>0) {
					$error=0;
					$msg_error="se agrego la nota del dia";
				} else {
					$error=1;
					$msg_error="no se pudo agregar la nota del dia en la tabla notas, por favor intentelo de nuevo";
				}
			} else {
				$error=1;
				$msg_error="no se pudo insertar la actividad, por favor intentelo nuevamente";
			}
		}
		
		
		/****** si hay un numero de nota la actualizamos ******/		
		//if ($accion==4 && isset($_POST['no_nota']) && !empty($_POST['no_nota'])) {
		if ($accion==4 && isset($_POST['no_nota'])) {
			/****** actualizamos la nota en la base de datos ******/
			$no_nota=texto_seguro($_POST['no_nota'], $link);
			$consulta="UPDATE notas SET nota='{$nota}' WHERE id='{$no_nota}'";
			$actualizar_nota=consulta($consulta, $link);
			$no_reg_afectados=mysqli_affected_rows($link);
			if ($no_reg_afectados>=0) {
				//echo "se actualizo la noda del dia";
			} else {
				$error=1;
				$msg_error="no se actualizo lo nota del dia, por favor intentelo de nuevo";
			}			
		}
		
		
		/****** si hay un numero de actividad la nota corresponde a esa actividad ******/
		if ($accion==4 && empty($_POST['no_nota']) && !empty($no_actividad)) {
			/****** no hay numero de nota pero si numero de actividad, agregamos la nota a esa actividad ******/
			$no_actividad=texto_seguro($_POST['no_actividad'], $link);
			$consulta="INSERT INTO notas(id_actividad, nota, tipo_nota) 
					   VALUES('{$no_actividad}', '{$nota}','NA')";
			$agregar_nota_actividad=consulta($consulta, $link);
			if (mysqli_affected_rows($link)) {
				$error=0;
				$msg_error="se agrego la nota a la actividad";
			} else {
				$error=1;
				$msg_error="no se pudo agregar la nota a la actividad, por favor intentelo de nuevo";
			}
		}
	}
	
	
	
	
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
	<div id="cuadro_actividades">
		<?php
			$fecha=explode("-", texto_seguro($_SESSION['fecha_hoy'], $link));
			$dia=intval($fecha[2]);
			$mes=$fecha[1];
			$anio=$fecha[0];
			$mes=mes_letra($mes);
		?>
		<h2>Actividades para el dia <?php echo $dia." de ".$mes." del ".$anio; ?></h2>
		<form name="form_actividades" id="form_actividades" action="<?php echo basename(__FILE__); ?>" method="post">
		<input type="hidden" name="no_actividad" id="no_actividad" value="">
		<input type="hidden" name="no_nota" id="no_nota" value="">
		<input type="hidden" name="accion" id="accion" value="">
		<input type="hidden" name="fecha_trabajo" value="">
		<ul>
		<?php 
			/****** buscamos si ya tiene alguna actividad para el dia de hoy ******/
			
			$consulta="SELECT id, actividad, completada
					   FROM actividad
					   WHERE id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."' AND fecha='".texto_seguro($_SESSION['fecha_hoy'], $link)."' AND actividad<>''";
			$buscar_actividades=consulta($consulta, $link);
			//var_dump($buscar_actividades);
			$num_actividades=mysqli_num_rows($buscar_actividades);
			if ($num_actividades>0) {
				/****** ponemos las actividades que ya tiene ******/
				if ($num_actividades>1) {
					echo " tiene <b>$num_actividades</b> actividades<br>";
				} else {
					echo " tiene <b>$num_actividades</b> actividad<br>";
				}				
				//while ($columna = mysql_fetch_assoc($buscar_actividades)) { 
				//echo "::".$accion."::";
				while ($columna = mysqli_fetch_array($buscar_actividades, MYSQLI_ASSOC)) { 				
					if ($accion==3 && $no_actividad==$columna['id']) { 					
					?>
						<li ><span class="task">
							<input type="text" name="modificar_actividad" id="modificar_actividad" value="<?php echo $columna['actividad']; ?>" /></span>							
							<span class="edicion">
							<a href="#" onclick="document.form_actividades.no_actividad.value='<?php echo $columna['id'] ?>';form_actividades.accion.value='3';form_actividades.submit();">guardar</a>&nbsp;&nbsp;
							<a href="#" onclick="form_actividades.no_actividad.value='';form_actividades.accion.value='';form_actividades.submit();">cancelar</a>
						</li>
					<?php
					} else {							
						?>
						<li ><span class="task <?php if ($columna['completada']==1) { echo 'hecho'; } ?>"><input type="checkbox" name="tarea_hecha_<?php echo $columna['id']; ?>" <?php if ($columna['completada']==1) { echo 'checked="checked" '; } ?> onclick="document.form_actividades.no_actividad.value='<?php echo $columna['id'] ?>';document.form_actividades.accion.value='6';document.form_actividades.submit();"/>&nbsp;<?php echo /*"<b>({$columna['id']})</b>".*/$columna['actividad']; ?></span>
						<span class="edicion"><a href="#" onclick="if (confirm('Esta accion no podra deshacerse.\n&iquest;Continuar?')){form_actividades.no_actividad.value='<?php echo $columna['id'] ?>';form_actividades.accion.value='2';form_actividades.submit();}">borrar</a>&nbsp;&nbsp;
						<a href="#" onclick="document.form_actividades.no_actividad.value='<?php echo $columna['id'] ?>';document.form_actividades.accion.value='3';document.form_actividades.submit();">editar</a>&nbsp;&nbsp;
						<a href="#" onclick="form_actividades.no_actividad.value='<?php echo $columna['id'] ?>';form_actividades.accion.value='5';form_actividades.submit();">nota</a></span>
						</li>
						
					<?php						
					}
				} // del while			
			} 
			if ($num_actividades<6) {
				/****** ponemos la opcion de agregar una actividad mas ******/
				//echo " puede agregar otra actividad";
				?>
				<li><input type="text" name="nueva_actividad" id="nueva_actividad" /><input type="submit" value="Agregar" /></li>
				<?php
				
			} else {
				/****** solo se permiten 6 actividades por dia ******/
				echo "solo se permiten 6 actividades por dia";
			}			
			
		if ($error==1) { ?>
			<li><span class="error"><?php echo $msg_error; ?></span></li>
		<?php
			}
		
		?>
		</ul>
		
	</div>
	<div id="notas">
		<span>
			<?php 
			/****** buscamos si hay notas ya sea del dia o de una actividad ******/
			$nota="";
			$id_nota='';
			if (isset($no_actividad) && !empty($no_actividad)) {
			/****** buscamos la nota de la actividad   ******/
				$consulta="SELECT  notas.id, notas.nota
						   FROM actividad, notas
						   WHERE actividad.id=notas.id_actividad AND notas.tipo_nota='NA' AND actividad.id=$no_actividad";
				$nota_actividad=consulta($consulta, $link);
				$no_notas=mysqli_num_rows($nota_actividad);
				if ($no_notas>0) {
					$fila = mysqli_fetch_array($nota_actividad, MYSQLI_ASSOC);
					//$nota=mysql_result($nota_actividad,0,1);
					$nota=$fila["nota"];
					//$id_nota=mysql_result($nota_actividad,0,0);
					$id_nota=$fila["id"];
				} else {
					//echo "no existe notas para esta actividad";
				}
				mysqli_free_result($nota_actividad);
			} else {
				/****** buscamos la nota del dia ******/
				$consulta="SELECT notas.id, notas.nota
						   FROM actividad, notas
						   WHERE actividad.actividad='' AND actividad.completada='1' AND actividad.id=notas.id_actividad
						         AND notas.tipo_nota='ND' AND actividad.id_usuario='".texto_seguro($_SESSION['id_usuario'], $link)."'
								 AND actividad.fecha='".texto_seguro($_SESSION['fecha_hoy'], $link)."'";
				$nota_del_dia=consulta($consulta, $link);
				$no_notadia=mysqli_num_rows($nota_del_dia);
				if ($no_notadia>0) {					
					$fila = mysqli_fetch_array($nota_del_dia,MYSQLI_ASSOC);
					$nota=$fila["nota"];
					$id_nota=$fila["id"];
				} else {
					//echo "no hay nota del dia en la base de datos";
				}
			}
			?>			
			<h3><?php if($accion!=5 ){ echo 'Apuntes del dia';} else { echo 'Notas de la actividad ' . $no_actividad ;} ?></h3>
			<textarea name="nota" id="nota" cols="30" rows="10" ><?php echo $nota; ?></textarea>
			<span class="edicion">
				<a href="#" onclick="form_actividades.no_nota.value='<?php echo $id_nota; ?>';form_actividades.accion.value='4';<?php if(!empty($no_actividad)){?> form_actividades.no_actividad.value='<?php echo $no_actividad; ?>';<?php } ?>form_actividades.submit();">guardar</a>
				<?php if (isset($no_actividad) && !empty($no_actividad)) {?> &nbsp;&nbsp;<a href="#" onclick="form_actividades.no_actividad.value='';form_actividades.accion.value='';form_actividades.submit();">nota del dia</a><?php } ?>
		</span>
	</div>
	</form>
	</div>
	</body>
</html>
<?php
	
	mysqli_free_result($buscar_actividades);
	mysqli_close($link);
} else { ?>
	<h3>Para poder ver sus actividades debes ingresar a tu cuenta, click <a href="index.php">aqui</a> para ingresar</h3>
	<meta http-equiv="refresh" content="2;index.php">
<?php
} // cierre del else
?>