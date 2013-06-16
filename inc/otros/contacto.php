<?php
	require_once "inc/config.php";
	head("Contacto");
	
	// VALIDADOR
	require_once("inc/validador.class.php");
	$Validador = new Validador();
	$Validador->SetInput(array('name' => 'nombreapellidos', 'alias' => 'Nombre y apellidos', 'min' => '6'));
	$Validador->SetInput(array('name' => 'email', 'alias' => 'Email', 'formato' => '^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$'));
	$Validador->SetInput(array('name' => 'asunto', 'alias' => 'Asunto', 'min' => '4'));
	$Validador->SetInput(array('name' => 'mensaje', 'alias' => 'Mensaje', 'min' => '4'));
	
	echo "<script>";
	$Validador->GeneraValidadorJS();
	echo "</script>";
?>
<body id="seccion_contacto">
	<ul id="menudrop">
		<li><a href="login.php">Login</a></li>
		<li><a href="inicio.php">Inicio</a></li>
		<li><a href="registro.php">Registro</a></li>
		<li><a href="otros.php?contacto=1">Contacto</a></li>
	</ul>

	<h2 class="encabezado">Contacto</h2>
	<br>
<div class="marco">
	<?php
	if($_POST['enviado']){
		$destinatario_email = Email_Address;
		$destinatario_name = $_POST['nombreapellidos'];
		$titulo = $_POST['asunto']." - ".$_POST['nombreapellidos'];

		// No separar del borde
		$mensaje = "
		<b>Emisor:</b> ".$_POST['nombreapellidos']."<br>
		<b>Asunto:</b> ".$_POST['asunto']."<br>
		<b>Email:</b> ".$_POST['email']."<br>
		<b>Mensaje:</b><br><br> ".nl2br($_POST['mensaje']);

		$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
		if($email_state != TRUE){
			print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
		}else{
			print "<div class='centrar'><div class='ok_ajustable'>El correo se ha enviado correctamente, en breve contactaremos contigo.</div></div><br>";
		}
	}
	?>
	<form method='post' action='otros.php?contacto=1'>
		<div class="input">
			<span>
				<input name="nombreapellidos" class="validable" type="text" value="<?php echo $_POST['nombreapellidos']; ?>" placeholder="Tu nombre y apellidos">
			</span>
		</div><br>
		<div class="input">
			<span>
				<input name="email" class="validable" type="text" value="<?php echo $_POST['email']; ?>" placeholder="Tu direccion email">
			</span>
		</div><br>
		<div class="input">
			<span>
				<input name="asunto" class="validable" type="text" value="<?php echo $_POST['asunto']; ?>" placeholder="El asunto del mensaje">
			</span>
		</div><br>
		<div class="input">
			<span>
				<textarea name="mensaje" class="validable" placeholder="Escribe aqui tu mensaje..."><?php echo $_POST['mensaje']; ?></textarea>
			</span>
		</div><br>
		<button type='button' class="azul" onclick="validador('submit')">
			<span><b>Enviar</b></span>
		</button>
		<input type="hidden" name="enviado" value="1" />
	</form>
</div>
</body>
</html>


