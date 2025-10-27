<?php
require ('inc/config.php'); // Asumo que este archivo establece la conexión $link y otras constantes
head("Registro");
?>
</head>
<body id="seccion_registro">
	<ul id="menudrop">
		<li>
			<a href="login.php">Login</a>
		</li>
	</ul>

	<h2 class="encabezado">Registro de usuario</h2>
	<br>
	<br>
	<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Registro'])) {

		$nombre = trim($_POST['Nombre'] ?? '');
		$apellidos = trim($_POST['Apellidos'] ?? '');
		$contrasenia_plain = $_POST['contrasenia'] ?? ''; // Mantener sin trim por si hay espacios intencionados
		$email = trim($_POST['Email'] ?? '');
		$nacimiento_display = $_POST['nacimiento'] ?? ''; // Valor para mostrar en el campo
		$nacimiento_hidden = $_POST['nacimiento_hidden'] ?? ''; // Formato YYYY-MM-DD para DB
		$sexo = $_POST['Sexo'] ?? '';
		$provincia = $_POST['Provincia'] ?? ''; // Asegúrate de que este campo existe en el formulario
		$tos_accepted = isset($_POST['tos']) && $_POST['tos'] === 'tos_yes'; // Verificar si los términos se aceptaron

		if (empty($nombre) || empty($apellidos) || empty($contrasenia_plain) || empty($email) || empty($nacimiento_hidden) || !$tos_accepted) {
			?>
			<div class="centrar">
				<div class="error_ajustable">
					<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">El Registro no se ha llevado a cabo</h3>
					Debes rellenar todos los campos correctamente y aceptar los T&eacute;rminos &amp; condiciones de uso para poder completar tu registro.
				</div>
			</div>
			<?php
		} else {

			$sql_existe_email = "SELECT 1 FROM usuarios WHERE email = ? LIMIT 1";
			if ($stmt_existe = mysqli_prepare($link, $sql_existe_email)) {
				mysqli_stmt_bind_param($stmt_existe, "s", $email);
				mysqli_stmt_execute($stmt_existe);
				mysqli_stmt_store_result($stmt_existe);

				if (mysqli_stmt_num_rows($stmt_existe) != 0) {
					?>
					<div class="centrar">
						<div class="error_ajustable">
							<h3 style="text-align: center;color: red;margin:5px 5px 30px 5px;">El Registro no se ha llevado a cabo</h3>
							El correo electronico (email) que has usado ya ha sido registrado, por favor utiliza otra direccion de correo eletronico.<br>
						</div>
					</div>
					<?php
				} else {
					if ($sexo == "h") {
						$img_princ = 1;
					} elseif ($sexo == "m") {
						$img_princ = 2;
					} else {
						$img_princ = NULL;
					}

					$destinatario_email = $email;
					$destinatario_name = $nombre . " " . $apellidos;
					$titulo = "Registro - " . SITE;

					// $codigo_activacion = 1; es para activación por defecto.
					// $codigo_activacion = bin2hex(random_bytes(16));
					$codigo_activacion = 1;

					$mensaje = "
					<html>
					<body style=\"background-color:#3869A0;text-align:center;padding:20px;\">
					<b style=\"color:white;font-size:40px;\">" . SITE . "</b><br>
					<div style=\"background-color:white;border-radius:10px;display: inline-block;
					margin: 10px;padding:20px;text-align:left;font-size:15px;\">
					Hola " . $nombre . ", has iniciado el proceso de registro en " . SITE . ".<br>
					Para completarlo, visita la siguiente direccion:<br>
					<a href=\"http://" . SITE_URL . "/otros.php?activar_cuenta=1&amp;
					codigo=" . $codigo_activacion . "\">
					http://" . SITE_URL . "/otros.php?activar_cuenta=1&amp;codigo=" . $codigo_activacion . "</a>
					<br><br><center><i style=\"font-size:12px; color: grey;\">" . SITE . " (c)</i></center>
					</div>
					</body></html>";

					// Verificación de email bypass temporal
					//$email_state = email_send($destinatario_name, $destinatario_email, $titulo, $mensaje);
					//if($email_state != TRUE){
					//	//TODO: Avisar fallo envio email
					//}

					$password_hash = password_hash($contrasenia_plain, PASSWORD_BCRYPT);

					$sql = "INSERT INTO usuarios (nombre, apellidos, fnac, password, email, fecha_reg, sexo, idfotos_princi, provincia, activacion)
							VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";

					if ($stmt = mysqli_prepare($link, $sql)) {
						mysqli_stmt_bind_param($stmt, "sssssssss",
							$nombre,
							$apellidos,
							$nacimiento_hidden,
							$password_hash,
							$email,
							$sexo,
							$img_princ,
							$provincia,
							$codigo_activacion
						);

						if (mysqli_stmt_execute($stmt)) {
							?>
							<div class="centrar">
								<div class="marco">
									<h3 style="text-align: center;color: green;margin:5px 5px 30px 5px;">Registro completado con &eacute;xito</h3>
									<?php echo $nombre; ?>, tu cuenta ha sido creada correctamente.<br>
										Ya puedes iniciar sesión.
								</div>
							</div>
							<?php
							mysqli_stmt_close($stmt);
							die();
						} else {
							echo "Error al ejecutar la consulta de inserción: " . mysqli_stmt_error($stmt);
							error_mysql();
						}
						mysqli_stmt_close($stmt);
					} else {
						echo "Error al preparar la consulta de inserción: " . mysqli_error($link);
						error_mysql();
					}
				}
				mysqli_stmt_close($stmt_existe);
			} else {
				echo "Error al preparar la consulta de verificación de email: " . mysqli_error($link);
				error_mysql();
			}
		}
	}
	?>
	<script>
		// JS REGISTRO (CALENDARIO CUSTOM)
		$(function() {
			var date = new Date();
			var year = date.getFullYear();
			var top_year = year - 14;
			$("#nacimiento").datepicker({
				changeMonth : true,
				changeYear : true,
				yearRange : "1940:"+top_year,
				defaultDate : "-20 y",
				dateFormat: "d 'de' MM 'de' yy",
				altField: "#nacimiento_hidden",
				altFormat: "yy-mm-dd",
			});
		});

	</script>
	<div class="centrar">
		<div class="marco">
			<div id="error_ajustable"></div>
				<form name="registro" method='post' action='registro.php'>
					<table>
						<tr>
							<td> Nombre </td>
							<td>
							<div class="input">
								<span>
									<input id="Nombre" name="Nombre" class="validable" type="text" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" placeholder="Nombre">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Apellidos </td>
							<td>
							<div class="input">
								<span>
									<input id="Apellidos" name="Apellidos" class="validable" type="text" value="<?php echo htmlspecialchars($apellidos ?? ''); ?>" placeholder="Apellidos">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Contrase&ntilde;a </td>
							<td>
							<div class="input">
								<span>
									<input id="contrasenia" name="contrasenia" class="validable" type="password" value="" placeholder="Contrase&ntilde;a">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Email </td>
							<td>
							<div class="input">
								<span>
									<input id="Email" name="Email" class="validable" type="text" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Email">
								</span>
							</div></td>

						</tr>
						<tr>
							<td> Fecha nacimiento </td>
							<td>
								<div class="input">
									<span>
										<input id="nacimiento" name="nacimiento" class="validable" type="text" value="<?php echo htmlspecialchars($nacimiento_display ?? ''); ?>">
										<input id="nacimiento_hidden" name="nacimiento_hidden" type="text" style="display:none;" value="<?php echo htmlspecialchars($nacimiento_hidden ?? ''); ?>" placeholder="Fecha nacimiento">
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<td> Provincia </td>
							<td>
							<div class="input">
								<span class="select">
									<select id="provincia" name="Provincia" class="validable">
										<?php
										require ("inc/select_provincias.html");
										?>
									</select> </span>
							</div></td>
						</tr>
						<tr>
							<td> Sexo </td>
							<td>
							<div style="word-spacing: 40px;">
								<input type="radio" class="validable" name="Sexo" value="h" id="sexo_hombre" <?php echo ($sexo === 'h') ? 'checked' : ''; ?>/>
								<label for="sexo_hombre" class="label_radio label_Sexo"> </label><label for="sexo_hombre">Hombre</label>
								<input type="radio" class="validable" name="Sexo" value="m" id="sexo_mujer" <?php echo ($sexo === 'm') ? 'checked' : ''; ?>/>
								<label for="sexo_mujer" class="label_radio label_Sexo"></label><label for="sexo_mujer">Mujer</label>
							</div></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
								<div class="checkbox">
									<input type="checkbox" id="checkbox_tos" name="tos" class="validable" value="tos_yes" <?php echo ($tos_accepted ?? false) ? 'checked' : ''; ?>>
									<label for="checkbox_tos" name="tos">Acepto los <a href="otros.php?tos=1" target="_blank">terminos de uso</a></label>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
							
							<input type="hidden" name="Registro" value="yes"/>
							<button type='submit' name='registro' value='Registrarse' class="azul" onclick="validador('submit')">
								<span><b>Registrarse</b></span>
							</button></td>
						</tr>
					</table>
				</form>
		</div>
	</div>
