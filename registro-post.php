<?php
if(	(!$_POST['Nombre'] OR !$_POST['Apellidos'] OR !$_POST['contrasenia'] OR !$_POST['Email'] OR !$_POST['nacimiento']) AND $_POST['Registro']){
		//TODO: Log este fallo, no deberia haber sucedido
		?>
		<div class="centrar">
			<div class="error_ajustable">
				<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">El Registro no se ha llevado a cabo</h3>
				Debes rellenar todos los campos correctamente  y aceptar los T&eacute;rminos &amp; condiciones de uso para poder completar tu registro.
			</div>
		</div>
		<?php
}else{
	$existe = mysqli_query($link,"SELECT * FROM usuarios WHERE email='".$_POST['Email']."'");

	if(mysqli_num_rows($existe) !="0"){
		?>
		<div class="centrar">
			<div class="error_ajustable">
				<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">El Registro no se ha llevado a cabo</h3>
				El correo electronico (email) que has usado ya ha sido registrado, por favor utiliza otra direccion de correo eletronico.<br>
				
			</div>
		</div>
		<?php
	}else{
		if($_POST['Sexo']=="h"){
			$img_princ=1;
		}elseif($_POST['Sexo']=="m"){
			$img_princ=2;
		}else{
			$img_princ=NULL;
		}
		$destinatario_email = $_POST['Email'];
		$destinatario_name = $_POST['Nombre']." ".$_POST['Apellidos'];
		$titulo = "Registro - ".Sitio;
		
		$codigo_activacion = rand(100000000, 999999999);
		
// No separar del borde
$mensaje = "
<html>
<body style=\"background-color:#3869A0;text-align:center;padding:20px;\">
<b style=\"color:white;font-size:40px;\">".Sitio."</b><br>
<div style=\"background-color:white;border-radius:10px;display: inline-block;
margin: 10px;padding:20px;text-align:left;font-size:15px;\">
Hola ".$_POST['Nombre'].", has iniciado el proceso de registro en ".Sitio.".<br>
Para completarlo, visita la siguiente direccion:<br>
<a href=\"http://".Sitio_direccion."/otros.php?activar_cuenta=1&amp;
codigo=".$codigo_activacion."\">
http://".Sitio_direccion."/otros.php?activar_cuenta=1&amp;codigo=".$codigo_activacion."</a>
<br><br><center><i style=\"font-size:12px; color: grey;\">".Sitio." (c)</i></center>
</div>
</body></html>";

		$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
		if($email_state != TRUE){
			//TODO: Avisar fallo envio email
		}
		
		mysqli_query($link,"INSERT INTO usuarios (nombre, apellidos, fnac, password, email, fecha_reg, sexo, idfotos_princi, provincia, activacion)
			values ( '".$_POST['Nombre']."', '".$_POST['Apellidos']."', '".$_POST['nacimiento_hidden']."', '".sha1($_POST['contrasenia'])."',
			 '".$_POST['Email']."', now(), '".$_POST['Sexo']."', '{$img_princ}', '".$_POST['Provincia']."', '{$codigo_activacion}' )");
		error_mysql();
		?>
		<div class="centrar">
			<div class="marco">
				<h3 style="text-align: center;color: green;margin:5px 5px 30px 5px;">Registro completado con &eacute;xito</h3>
				<?php echo $_POST['Nombre']; ?>, tu cuenta ha sido creada correctamente.<br>
				Te hemos enviado un correo a tu direccion email con un enlace que debes visitar para completar el registro.<br>
			</div>
		</div>
		<?php
		die();
	}
}
?>