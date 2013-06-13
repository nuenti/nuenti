<?php
require ('inc/config.php');
head("Registro");
?>
<script type="text/javascript" src="jscripts/registro_js.php"></script>
<script type="text/javascript" src="jscripts/forms.js"></script>
</head>
<body id="seccion_registro">
	<ul id="menudrop">
		<li>
			<a href="login.php">Login</a>
		</li>
		<li>
			<a href="contacto.php">Contacto</a>
		</li>
	</ul>

	<h2 class="encabezado">Registro de usuario</h2>
	<br>
	<br>
	<?php
		if ($_POST) {
			require ("registro-post.php");
		}
	?>
	<script>
	</script>
	<div class="centrar">
		<div class="marco">
			<div id="error_ajustable"></div>
				<form name="registro" method='post' action='registro.php' autocomplete="off">
					<table>
						<tr>
							<td> Nombre </td>
							<td>
							<div class="input">
								<span>
									<input id="Nombre" name="Nombre" class="validable" type="text" value="<?php echo $_POST['Nombre']; ?>" autocomplete="off" placeholder="Nombre">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Apellidos </td>
							<td>
							<div class="input">
								<span>
									<input id="Apellidos" name="Apellidos" class="validable" type="text" value="<?php echo $_POST['Apellidos']; ?>" autocomplete="off" placeholder="Apellidos">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Contrase&ntilde;a </td>
							<td>
							<div class="input">
								<span>
									<input id="contrasenia" name="contrasenia" class="validable" type="password" value="" autocomplete="off" placeholder="Contrase&ntilde;a">
								</span>
							</div></td>
						</tr>
						<tr>
							<td> Email </td>
							<td>
							<div class="input">
								<span>
									<input id="Email" name="Email" class="validable" type="text" value="<?php echo $_POST['Email']; ?>" autocomplete="off" placeholder="Email">
								</span>
							</div></td>

						</tr>
						<tr>
							<td> Fecha nacimiento </td>
							<td>
								<div class="input">
									<span>
										<input id="nacimiento" name="nacimiento" class="validable" type="text" value="<?php echo $_POST['nacimiento']; ?>" autocomplete="off">
										<input id="nacimiento_hidden" name="nacimiento_hidden" type="text" style="display:none;" value="<?php echo $_POST['nacimiento_hidden']; ?>" autocomplete="off" placeholder="Fecha nacimiento">
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
								<input type="radio" class="validable" name="Sexo" value="h" id="sexo_hombre"/>
								<label for="sexo_hombre" class="label_radio label_Sexo"> </label><label for="sexo_hombre">Hombre</label>
								<input type="radio" class="validable" name="Sexo" value="m" id="sexo_mujer"/>
								<label for="sexo_mujer" class="label_radio label_Sexo"></label><label for="sexo_mujer">Mujer</label>
							</div></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
								<div class="checkbox">
									<input type="checkbox" id="checkbox_tos" name="tos" class="validable" value="tos_yes">
									<label for="checkbox_tos" name="tos">Acepto los <a href="post.php?tos=1" target="_blank">terminos de uso</a></label>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
							
							<input type="hidden" name="Registro" value="yes"/>
							<button type='button' name='registro' value='Registrarse' class="azul" onclick="validador('submit')">
								<span><b>Registrarse</b></span>
							</button></td>
						</tr>
					</table>
				</form>
		</div>
	</div>