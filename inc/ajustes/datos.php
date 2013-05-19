<?php
	$sql = "SELECT *, DATE_FORMAT( fnac, '%d/%m/%Y' ) AS fnac2 FROM usuarios WHERE idusuarios = '{$global_idusuarios}'";
	$q_user = mysqli_query($link, $sql);
	$r_user = mysqli_fetch_assoc($q_user);
?>

<script>
	$(function(){
		$("#fecha").datepicker({
			altField: "#fecha_hidden",
			altFormat: "yymmdd",
			changeMonth: true,
			changeYear: true,
			yearRange: "1940:2000"
		});
	});
	
	$(document).ready(function(){
		// Marcar las opciones por defecto
		$("select#provincia").find("option[value='<?php echo $r_user['provincia']; ?>']").attr("selected","true");
		$("input[name='sexo'][value='<?php echo $r_user['sexo']; ?>']").attr("checked","true");
		$( "#fecha" ).datepicker( "setDate", "<?php echo $r_user['fnac2']; ?>" );
	});
	
	function cambio_email(){
		email_new = $("#cambio_email").find("#email_new").val();
		email = $("#cambio_email").find("#email").val();
		pass = $("#cambio_email").find("#pass").val();
		
		ajax_post({
			data : "cambio_email=1&email_new="+email_new+"&email="+email+"&pass="+pass,
			reload : true,
		});
	}
	
	function cambio_pass(){
		pass_new = $("#cambio_pass").find("#pass_new").val();
		pass_new2 = $("#cambio_pass").find("#pass_new2").val();
		pass = $("#cambio_pass").find("#pass").val();
		
		ajax_post({
			data : "cambio_pass=1&pass_new="+pass_new+"&pass_new2="+pass_new2+"&pass="+pass,
			reload : true,
		});
	}
	
	function cambio_datos(){
		nombre = $("#cambio_datos").find("#nombre").val();
		apellidos = $("#cambio_datos").find("#apellidos").val();
		sexo = $("#cambio_datos").find("input[name='sexo']").val();
		provincia = $("#cambio_datos").find("#provincia").val();
		fecha_hidden = $("#cambio_datos").find("#fecha_hidden").val();
		
		ajax_post({
			data : "cambio_datos=1&nombre="+nombre+"&apellidos="+apellidos+"&sexo="+sexo+"&provincia="+provincia+"&fecha_hidden="+fecha_hidden,
			reload : true,
		});
	}
</script>

<div id="cambio_email" class="">
	<h3>Cambio de email</h3>
	Email nuevo: <input type="text" id="email_new" /><br>
	
	Email antiguo: <input type="text" id="email" /><br>
	Contraseña: <input type="password" id="pass" /><br>

	<button onclick="cambio_email()">Cambiar email</button>
</div>

<div id="cambio_pass" class="">
	<h3>Cambio de contraseña</h3>
	Contraseña nueva: <input type="password" id="pass_new" /><br>
	Repite la contraseña nueva: <input type="password" id="pass_new2" /><br>
	
	Contraseña antigua: <input type="password" id="pass" /><br>

	<button onclick="cambio_pass()">Cambiar contraseña</button>
</div>

<div id="cambio_datos" class="">
	<h3>Cambio de datos</h3>
	Nombre: <input type="text" id="nombre" value="<?php echo $r_user['nombre']; ?>" /><br>
	Apellidos: <input type="text" id="apellidos" value="<?php echo $r_user['apellidos']; ?>" /><br>
	Sexo: 
	<input type="radio" name="sexo" value="h" style="display: inline;" /> Hombre
	<input type="radio" name="sexo" value="m" style="display: inline;" /> Mujer<br />
	
	Provincia: <select id="provincia">
				<?php require 'inc/select_provincias.html'; ?>
				</select><br>
				
	Fecha nacimiento: <input type="text" id="fecha" value="<?php echo $r_user['fecha']; ?>" /><br>
		<input type="hidden" id="fecha_hidden" value="<?php echo $r_user['fnac']; ?>"/>
		
	<button onclick="cambio_datos()">Cambiar datos</button>
</div>


<?php
/*
 * //TODO: Pendiente de añadir
		COLEGIOS
		ESTADO CIVIL
		INTERESES:
			Aficiones
			Grupos musicales, discos
			Citas famosas
			Libros, escritores, géneros
			Películas, directores, actores
	 */
?>