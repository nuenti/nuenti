<?php
head("Recuperar la contraseña");
?>
<body id="seccion_restore_pass">
	<ul id="menudrop">
		<li><a href="login.php">Login</a></li>
		<li><a href="inicio.php">Inicio</a></li>
		<li><a href="registro.php">Registro</a></li>
		<li><a href="otros.php?contacto=1">Contacto</a></li>
	</ul>

	<br>
	<div class="barra_full">
		<div class="marco">
			
			<?php
			// ESCRIBIR EMAIL PARA RECUPERACION
			if($_POST['restore_pass'] AND $_POST['email']){
				$sql = "SELECT * FROM usuarios WHERE email='".$_POST['email']."'";
				$q_user = mysqli_query($link, $sql);


				if(mysqli_num_rows($q_user)==1){
					$codigo = rand(111111, 999999);
					
					$r_user = mysqli_fetch_assoc($q_user);
					$sql = "UPDATE usuarios SET recuperacion='".$codigo."' WHERE email='".$_POST['email']."'";
					mysqli_query($link , $sql);
					
					
					
					$destinatario_email = $_POST['email'];
					$destinatario_name = $r_user['nombre']." ".$r_user['apellidos'];
					$titulo = "Recuperar la contraseña - ".Sitio;

// No separar del borde
$mensaje = "
<html>
<body style=\"background-color:#3869A0;text-align:center;padding:20px;\">
<b style=\"color:white;font-size:40px;\">".Sitio."</b><br>
<div style=\"background-color:white;border-radius:10px;display: inline-block;
margin: 10px;padding:20px;text-align:left;font-size:15px;\">
Hola ".$r_user['nombre'].", alguien ha solicitado recuperar 
la contrase&ntilde;a para tu cuenta de ".Sitio."<br>
Si has sido t&uacute;, abre la siguiente direccion para crear una contrase&ntilde;a nueva:<br><br>
<a href=\"http://".Sitio_direccion."/otros.php?restore_pass=1&amp;codigo=".$codigo."\">
http://".Sitio_direccion."/otros.php?restore_pass=1&amp;codigo=".$codigo."</a><br><br>
Si tu no has solicitado recuperar tu contrase&ntilde;a, ignora este correo.
<center><i style=\"font-size:12px; color: grey;\">".Sitio." (c)</i></center>
</div></body></html>";

				$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
				if($email_state != TRUE){
					print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
				}else{
					print "<div class='centrar'><div class='ok_ajustable'>Te hemos enviado un correo a tu email con las instrucciones para recuperar la contrase&ntilde;a, revisa tu bandeja de entrada.</div></div><br>";
				}
				die();
			}else{
				print "<div class='centrar'><div class='error_ajustable'>El correo electronico que has introducido no esta registrado</div></div>";
			}
		}
			
			
			// INTRODUCIR LA CONTRASEÑA NUEVA
			if($_GET['restore_pass'] AND $_GET['codigo']){
				
				$sql = "SELECT * FROM usuarios WHERE recuperacion = '".$_GET['codigo']."'";
				$q_user = mysqli_query($link, $sql);


				if(mysqli_num_rows($q_user)==1){
					?>
					Ahora introduce la contrase&ntilde;a nueva para tu cuenta:<br><br>
					<form method='POST' action='otros.php'>
						<div class="input">
							<span>
								<input name="pass" type="password" autofocus placeholder="Contrase&ntilde;a nueva">
							</span>
						</div>
						<button type='submit' class="azul"><span><b>Guardar</b></span></button>
						<input type="hidden" name="restore_pass" value="1">
						<input type="hidden" name="codigo" value="<?php echo $_GET['codigo']; ?>">
					</form>
					<?php
					die();
				}else{
					print "<div class='centrar'><div class='error_ajustable'>El codigo no es correcto</div></div>";
				}
			}

			// GUARDAR LA CONTRASEÑA NUEVA
			if($_POST['restore_pass'] AND $_POST['codigo'] AND $_POST['pass']){
				
				$sql = "SELECT * FROM usuarios WHERE recuperacion = '".$_POST['codigo']."'";
				$q_user = mysqli_query($link, $sql);

				// codigo correcto
				if(mysqli_num_rows($q_user)==1){
					$r_user = mysqli_fetch_assoc($q_user);
					$sql = "UPDATE usuarios SET password = '".sha1($_POST['pass'])."' WHERE recuperacion = '".$_POST['codigo']."'";
					$q_pass = mysqli_query($link, $sql);
					
					//Cambio pass correcto
					if(mysqli_errno($link) == 0){
						$sql = "UPDATE usuarios SET recuperacion = '0' WHERE recuperacion = '".$_POST['codigo']."'";
						//mysqli_query($link, $sql);
					
						print "<div class='centrar'><div class='ok_ajustable'>Enhorabuena, tu contrase&ntilde;a nueva se a guardado, ya puedes acceder a <a href='login.php'>".Sitio."</a> con ella.<br>
								Tambien te hemos mandado un email a tu direccion con la contrase&ntilde;a nueva</div></div>";
												
						$destinatario_email = $r_user['email'];
						$destinatario_name = $r_user['nombre']." ".$r_user['apellidos'];
						$titulo = "Contraseña nueva - ".Sitio;
					
// No separar del borde
$mensaje = "
<html>
<body style=\"background-color:#3869A0;text-align:center;padding:20px;\">
<b style=\"color:white;font-size:40px;\">".Sitio."</b><br>
<div style=\"background-color:white;border-radius:10px;display: inline-block;
margin: 10px;padding:20px;text-align:left;font-size:15px;\">
Hola ".$r_user['nombre'].", la contraseña nueva para tu cuenta es:<br><br>
<b>".$_POST['pass']."</b><br><br>
Ya puedes entrar con ella:<a href=\"http://".Sitio_direccion."/login.php\">
http://".Sitio_direccion."/login.php</a><br>
<center><i style=\"font-size:12px; color: grey;\">".Sitio." (c)</i></center>
</div>
</body></html>";

						$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
						if($email_state != TRUE){
							print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
						}
						die();	
							
					// Cambio pass fail		
					}else{
						print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al cambiar la contrase&ntilde;a</div></div>";
					}
				}
			}
		?>
		
		Si has perdido tu contrase&ntilde;a puedes generar otra introduciendo tu email a continuacion,<br>
		 la nueva contrase&ntilde;a se enviara a tu correo electronico.<br><br>
		<form method='POST' action='otros.php'>			
			<div class="input">
				<span>
					<input name="email" type="text" value="<?php echo $_POST['email']; ?>" maxlength='40' autofocus placeholder="ejemplo@mail.com">
				</span>
			</div>
			<button type='submit' class="azul"><span><b>Enviar email</b></span></button>
			<input type="hidden" name="restore_pass" value="1">
		</form>
		</div>
	</div>
</body>
</html>