<?php
########	ACTIVAR CUENTA
if($_GET['activar_cuenta']){
	$sql = "SELECT * FROM usuarios WHERE activacion='{$_GET['codigo']}'";
	$q_user = mysqli_query($link, $sql);
	
	if (mysqli_num_rows($q_user) == 1) {
		mysqli_query($link, "UPDATE usuarios SET activacion = '1' WHERE activacion='{$_GET['codigo']}'");
		$r_user = mysqli_fetch_assoc($q_user);		
		
		$destinatario_email = $r_user['email'];
		$destinatario_name = $r_user['nombre']." ".$r_user['apellidos'];
		$titulo = "Registro completado - ".Sitio;
				
// No separar del borde
$mensaje = "
<html>
<body style=\"background-color:#3869A0;text-align:center;padding:20px;\">
<b style=\"color:white;font-size:40px;\">".Sitio."</b><br>
<div style=\"background-color:white;border-radius:10px;display: inline-block;
margin: 10px;padding:20px;text-align:left;font-size:15px;\">
¡Enhorabuena ".$r_user['nombre']."!, has completado con &eacute;xito
 el proceso de registro.<br><br>
<b>Tus datos son:</b><br>
Nombre: ".$r_user['nombre']." ".$r_user['apellidos']."<br>
Email: ".$r_user['email']."<br><br>
Ahora podr&aacute; disfrutar de ".Sitio.", no esperes m&aacute;s y
 entra ya<br><br>
<a href=\"http://".Sitio_direccion."\">http://".Sitio_direccion."/</a><br><br>
<center><i style=\"font-size:12px; color: grey;\">".Sitio." (c)</i></center>
</div>
</body></html>";
		
		$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
		if($email_state != TRUE){
			header( "Location: login.php?activacion=fail_email" );
		}
		header( "Location: login.php?activacion=ok" );
	}else{
		header( "Location: login.php?activacion=fail" );
	}
	die();
}
