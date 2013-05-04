<?php
if(	(!$_POST['Nombre'] OR !$_POST['Apellidos'] OR !$_POST['contrasenia'] OR
	$_POST['contrasenia'] != $_POST['contrasenia2'] OR !$_POST['Email'] OR !$_POST['nacimiento']) AND $_POST['Registro']){
		?>
		<div class="centrar">
			<div class="error">
				<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">Registro fallido</h3>
				Debes rellenar todos los campos correctamente para poder completar tu registro satisfactoriamente.<br>
				Las contraseñas deben coincidir<br>
				Debe aceptar los T&eacute;rminos &amp; condiciones de uso
		</div>
		<?php
}else{
	$existe = mysqli_query($link,"SELECT * FROM usuarios WHERE email='".$_POST['Email']."'");

	if(mysqli_num_rows($existe) !="0"){
		?>
		<div class="centrar">
			<div class="error">
				<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">Registro fallido</h3>
				El correo electronico (email) que has usado ya esta registrado,<br>
				debes usar otro correo electronico.<br>
			</div>
		</div>
		<?php
	}else{
		if($_POST['Sexo']=="H"){
			$img_princ=1;
		}elseif($_POST['Sexo']=="M"){
			$img_princ=2;
		}else{
			$img_princ=NULL;
		}
		
		mysqli_query($link,"INSERT INTO usuarios (nombre, apellidos, fnac, password, email, fecha_reg, sexo, idfotos_princi, provincia)
			values ('".$_POST['Nombre']."','".$_POST['Apellidos']."',STR_TO_DATE('".$_POST['nacimiento']."','%d/%m/%Y'),'".sha1($_POST['contrasenia'])."','".$_POST['Email']."',curdate(),'".$_POST['Sexo']."', '{$img_princ}','".$_POST['Provincia']."')");
		//error_mysql('exit');
		?>
		<div class="centrar">
			<div class="marco">
				<h3 style="text-align: center;color: green;margin:5px 5px 30px 5px;">Registro satisfactorio</h3>
				<?php echo $_POST['Nombre']; ?>, tu cuenta ha sido creada correctamente.<br>
				Te hemos enviado un correo a tu direccion email con tus datos<br>
				Pulsa <a href="index.php">aqu&iacute;</a> ya para acceder a tu cuenta
			</div>
		</div>
		<?php
		die();
	}
}
?>