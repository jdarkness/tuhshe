<?php
error_reporting(E_ALL);
// Turn off all error reporting
//error_reporting(0);
session_start();
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }/*
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }*/
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
	  /* no funciona en windows */
	  /* en los comentarios del manual de la funcion de php hay una alternativa*/
	  /* pero no lo implemento por complicaciones y cuando este en internet estara en linux*/
	  /* es mas barato el hosting :)*/
	  
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
	  
   }
   return $isValid;
}
/* para que funcione el checkdnsrr en windons   */

function win_checkdnsrr($host, $type='MX') {
    if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') { return; }
    if (empty($host)) { return; }
    $types=array('A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY');
    if (!in_array($type,$types)) {
        user_error("checkdnsrr() Type '$type' not supported", E_USER_WARNING);
        return;
    }
    @exec('nslookup -type='.$type.' '.escapeshellcmd($host), $output);
    foreach($output as $line){
        if (preg_match('/^'.$host.'/',$line)) { return true; }
    }
}

// Define
if (!function_exists('checkdnsrr')) {
    function checkdnsrr($host, $type='MX') {
        return win_checkdnsrr($host, $type);
    }
}

//*******************************************************************//

function texto_seguro($texto, $link) {	
	if (!empty($texto) || $texto != NULL) {		
		$texto=stripslashes($texto);
		$texto=mysqli_real_escape_string($link, $texto);
		$texto=htmlspecialchars($texto);		
		return $texto;
	}	
}

function conectar_a_bd($basededatos="actividad") {	
	// base de datos local
	$usuario='usuario';
	$contrasenia='password';	
	$host='localhost';
	$link = mysqli_connect($host, $usuario, $contrasenia, $basededatos);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}	
	/*
	$db_selected = mysqli_select_db($basededatos, $link);
	if (!$db_selected) {
		die ('Can\'t use '.$basededatos.' : ' . mysqli_error($link);
	}
	*/
	return $link;
}

function consulta($consulta, $link) {
	$result = mysqli_query($link, $consulta);
	/*var_dump($result)."\n<br><br>";
	var_dump($consulta)."\n<br><br>";*/
	//var_dump($link)."\n<br><br>";
	//printf("%s\n", mysqli_info($link));
	//echo "System status: ". mysqli_stat($link); 
	
	// Check result
	// This shows the actual query sent to MySQL, and the error. Useful for debugging.
	if (!$result) {
		$message  = 'Invalid query: ' . mysqli_errno($link)."::".mysqli_error($link) . "\n<br>";
		$message .= 'Whole query: ' . $consulta;
		die($message);
	}
	return $result;
}

function crear_salt() {	
	$salt=mt_rand(0,99999999);
	return $salt;
}

function encriptar_password($password, $salt) {
	//echo $password."::".$salt;
	$txtencriptado=utf8_encode($salt.$password);	
	for ($i=0; $i<1000; $i++) {
		//$txtencriptado=md5($txtencriptado);
		$txtencriptado=sha1($txtencriptado); 
	}
	return base64_encode($txtencriptado);
}

function calendario($mes, $anio) {
	/*** tomado de aqui http://evolt.org/node/60673 ***/
			
	/*** primero obtenemos el año el mes y el dia ***/
	//$anio = date('Y');			
	//$mes = date('n');
	//$dia = date('j');
	//echo "$anio-$mes-$dia<br>";
	$hoy=date('Y-n-j');
	
	/*** convertimos el mes a string ***/
	$str_mes='';
	switch ($mes) {
		case 1:
			$str_mes='Enero';
			break;
		case 2:
			$str_mes='Febrero';
			break;
		case 3:
			$str_mes='Marzo';
			break;
		case 4:
			$str_mes='Abril';
			break;
		case 5:
			$str_mes='Mayo';
			break;
		case 6:
			$str_mes='Junio';
			break;
		case 7:
			$str_mes='Julio';
			break;
		case 8:
			$str_mes='Agosto';
			break;
		case 9:
			$str_mes='Septiembre';
			break;
		case 10:
			$str_mes='Octubre';
			break;
		case 11:
			$str_mes='Noviembre';
			break;
		case 12:
			$str_mes='Diciembre';
			break;
	}
	
	
	/***buscamos los dias en el mes ***/
	$dias_en_el_mes = date("t",mktime(0,0,0,$mes,1,$anio));
	//echo "dias_mes:$dias_en_el_mes<br>";
	
	/*** buscamos el primer dia del mes ***/
	$primer_dia= date("w", mktime(0,0,0,$mes,1,$anio));
	//echo "primer_dia:$primer_dia<br>";
	
	/*** calculamos el numero de semanas en el mes ***/
	$temp_dias = $primer_dia + $dias_en_el_mes;
	$semanas_en_el_mes = ceil($temp_dias/7);
	//echo "semanas_mes:$semanas_en_el_mes<br>";
	
	/*** imprimimos los dias del mes ***/
	$str_calendario='';
	//$str_calendario="<br><br>";
	$str_calendario.='<table>'."\n";
	//$str_calendario.='<caption>'.date("F",mktime(0,0,0,$mes,1,$anio)).'</caption>';
	$str_calendario.='<caption>'.$str_mes.'</caption>'."\n";
	$str_calendario.='<tr>'."\n";
	$str_calendario.='<th>Do</th>'."\n";
	$str_calendario.='<th>Lu</th>'."\n";
	$str_calendario.='<th>Ma</th>'."\n";
	$str_calendario.='<th>Mi</th>'."\n";
	$str_calendario.='<th>Ju</th>'."\n";
	$str_calendario.='<th>Vi</th>'."\n";
	$str_calendario.='<th>Sa</th>'."\n";
	$str_calendario.='</tr>'."\n";
	$contador=1;
	for ($i=0; $i<$semanas_en_el_mes; $i++) {
		$str_calendario.='<tr>'."\n";
		for ($j=0; $j<7; $j++) {
			$fecha_trabajo=$anio.'-'.$mes.'-'.$contador;
			$class_hoy="";
			if ($hoy==$fecha_trabajo) {
				$class_hoy='class="hoy"';
				//echo $hoy.$fecha_trabajo;
			}
			//$_SESSION['fecha_hoy']
			if ($_SESSION['fecha_hoy']==$fecha_trabajo) {
				$class_hoy='class="fecha_trabajo"';
				//echo $hoy.$fecha_trabajo;
			}
			switch ($i) {
				case 0:
					if ($j<$primer_dia) {
						$str_calendario.='<td>&nbsp;</td>'."\n";
					} else {
						$str_calendario.='<td '.$class_hoy.'><a href="#" onclick="document.frmfecha.fecha_trabajo.value=\''.$fecha_trabajo.'\';document.frmfecha.submit();">'.$contador.'</a></td>'."\n";
						$contador++;
					}
					break;
				case ($semanas_en_el_mes-1):
					if ($contador>$dias_en_el_mes) {
						$str_calendario.='<td>&nbsp;</td>'."\n";
					} else {
						$str_calendario.='<td '.$class_hoy.'><a href="#" onclick="document.frmfecha.fecha_trabajo.value=\''.$fecha_trabajo.'\';document.frmfecha.submit();">'.$contador.'</a></td>'."\n";
						$contador++;
					}
					break;
				default:
					$str_calendario.='<td '.$class_hoy.'><a href="#" onclick="document.frmfecha.fecha_trabajo.value=\''.$fecha_trabajo.'\';document.frmfecha.submit();">'.$contador.'</a></td>'."\n";
					$contador++;
					break;
			}
		}
		$str_calendario.='</tr>'."\n";
	}
	$str_calendario.='</table>'."\n";
	return $str_calendario;
}

function log_to_db($pagina) {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$ip = $_SERVER['REMOTE_ADDR'];	
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$enter="<br><br>";
	//echo $user_agent . $enter . $ip . $enter . $fecha . $enter . $hora . $enter . $pagina;
	//$link = conectar_a_bd('actividad');
    $link = conectar_a_bd('u94051_boletin');
	$cadena_consulta ="INSERT INTO stats (user_agent, pagina, ip, fecha, hora) 
						VALUES ('$user_agent', '$pagina', '$ip', '$fecha', '$hora')";
	$resultado=consulta($cadena_consulta, $link);
}

function mes_letra($mes, $idioma="spanish") {
	$idioma=strtolower($idioma);
	$str_mes='';
	switch ($mes) {
		case 1:
			switch ($idioma) {
				case "spanish":
					$str_mes='Enero';
					break;				
			}			
			break;
		case 2:
			switch ($idioma) {
				case "spanish":
					$str_mes='Febrero';
					break;
			}						
			break;
		case 3:
			switch ($idioma) {
				case "spanish":
					$str_mes='Marzo';
					break;
			}
			break;
		case 4:
			switch ($idioma) {
				case "spanish":
					$str_mes='Abril';
					break;
			}			
			break;
		case 5:
			switch ($idioma) {
				case "spanish":
					$str_mes='Mayo';
					break;
			}
			break;
		case 6:
			switch ($idioma) {
				case "spanish":
					$str_mes='Junio';
					break;
			}
			break;
		case 7:
			switch ($idioma) {
				case "spanish":
					$str_mes='Julio';
					break;
			}
			break;
		case 8:
			switch ($idioma) {
				case "spanish":
					$str_mes='Agosto';
					break;
			}
			break;
		case 9:
			switch ($idioma) {
				case "spanish":
					$str_mes='Septiembre';
					break;
			}			
			break;
		case 10:
			switch ($idioma) {
				case "spanish":
					$str_mes='Octubre';
					break;
			}			
			break;
		case 11:
			switch ($idioma) {
				case "spanish":
					$str_mes='Noviembre';
					break;
			}
			break;
		case 12:
			switch ($idioma) {
				case "spanish":
					$str_mes='Diciembre';
					break;
			}
			break;
	}
	return $str_mes;	
}
?>