<?php
require('inc/config.php');
	head("Registro - Social");
	?>
	<script type="text/javascript" src="jscripts/valida_registro.php"></script>
	<script>
		$(function(){
			$("#datepicker").datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "1940:2000"
			});
		});
	</script>
</head>
<body>
	<ul id="menudrop">
		<li><a href="login.php">&lt;-- Login</a></li>
		<li><a href="contacto.php">Contacto</a></li>
	</ul>

	<h2 class="encabezado">Registro de usuario</h2>
	<br>
	<br>
	<?php

if($_POST){
	require("registro-post.php");
}
?>
	<div class="centrar">
		<div class="marco">
			<div id="valida_error"></div>
			<form name="registro" method='post' action='registro.php'>
				Nombre: <input type='text' class="validable" size='15' maxlength='20' name='Nombre' value="<?php echo $_POST['Nombre']; ?>" /><br />
				Apellidos: <input type='text' class="validable" size='25' maxlength='40' name='Apellidos'  value="<?php echo $_POST['Apellidos']; ?>" /><br />
				Contrase&ntilde;a: <input type='password' class="validable" size='15' name='contrasenia' /><br />
				Repita la contrase&ntilde;a: <input type='password' class="validable" size='15' name='contrasenia2' /><br />
				Email: <input type='text' class="validable" size='30' name='Email'  value="<?php echo $_POST['Email']; ?>" /><br />

				Fecha nacimiento: <input type='text' class="validable" size='15' name='nacimiento'  id="datepicker"  value="<?php echo $_POST['nacimiento']; ?>" /><br />
				
				Sexo: 
				<input type="radio" class="validable" name="Sexo" value="H" id="sexo_hombre"/><label for="sexo_hombre" class="label_radio label_Sexo"></label><label for="sexo_hombre">Hombre</label>
				<input type="radio" class="validable" name="Sexo" value="M" id="sexo_mujer"/><label for="sexo_mujer" class="label_radio label_Sexo"></label><label for="sexo_mujer">Mujer</label><br />
				
				<input type="checkbox" class="validable" name="tos" id="checkbox_tos" value="tos_yes"><label for="checkbox_tos">Acepto los terminos de uso</label><br>

				Provincia: <select name="Provincia" class="validable"><?php require("inc/select_provincias.html"); ?></select><br>
				<input type="hidden" name="Registro" value="yes"/>
				<button type='button' name='registro' value='Registrarse' onclick="validador('submit')">Registrarse</button>
			</form>
		</div>
	</div>