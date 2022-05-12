<?php
$actual=basename($_SERVER['PHP_SELF']);
?>

		<ul id="menu">
			<li><?php if ($actual=='index.php') {?> Inicio <?php } else {?> <a href="index.php">Inicio</a> <?php }?></li>
			<li>|<?php if ($actual=='pantallazos.php') {?> Screenshots <?php } else {?> <a href="pantallazos.php">Screenshots</a> <?php }?></li>
			<li>|<?php if ($actual=='login.php') {?> Acceder <?php } else {?> <a href="login.php">Acceder</a> <?php }?></li>		
			<li>|<?php if ($actual=='signup.php') {?> Crear Cuenta <?php } else {?> <a href="signup.php">Crear Cuenta</a> <?php }?></li>
		</ul>
	