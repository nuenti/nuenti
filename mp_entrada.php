<?php
	require("inc/verify_login.php");
	head("Mensajeria Privada - Social");
	require("inc/estructura.inc.php");

	print "<div class='barra_full'>
			<div class='marco'>
				<h2>Mensajeria Privada: Recibidos</h2>";

	$sql = "SELECT *, DATE_FORMAT(mps.fecha, '%d/%m/%Y %H:%i') AS fechaf FROM mps,usuarios LEFT JOIN fotos ON idfotos=idfotos_princi WHERE receptor='".$global_idusuarios."' AND idusuarios=emisor ORDER BY idmps DESC";
	$q_mensajes=mysqli_query($link,$sql);
	
	echo "<div style='border-top: 1px solid #E5E5E5;'>";
	while($r_mensajes=mysqli_fetch_assoc($q_mensajes)){
		
		$nombre_apellido = NombreApellido($r_mensajes['nombre']." ".$r_mensajes['apellidos']);
		
		if(strlen($r_mensajes['mp'])>20){
			$resumen_mp = substr($r_mensajes['mp'], 0, 20)."...";
		}else{
			$resumen_mp = $r_mensajes['mp'];
		}
		
		 print "
			<div id='{$r_mensajes['idmps']}' class='mp_entrante'>
				<div class='encabezado' onclick='MP_toggle(this)'>
					<img src='{$r_mensajes['archivo']}'>
					<div class='nombre'>{$nombre_apellido}</div>
					<div class='resumen'>{$resumen_mp}</div>
					<div class='fecha'>{$r_mensajes['fechaf']}</div>
				</div>
				<div class='cuerpo'>
					<div class='texto'>{$r_mensajes['mp']}</div>
					<form action='#' onsubmit='return false'>
						<input type='hidden' name='receptor' value='{$r_mensajes['emisor']}'>
						<textarea name='mensaje' class='validable' cols='60' rows='2'></textarea><br>
						<button type='button' class='azul' onclick='mp_enviar(this);'><span><b>Responder</b></span></button>
					</form>
				</div>
			</div>
		";
		//echo "<h3 onclick=\"ajax('post.php?mensaje_leido=".$mps['idmps']."')\">".$mps['nombre']." te dijo: ".$mps['fechaf']."</h3>";
		//echo "<div><p>".$mps['mp']."</p></div>";
	}
	echo "</div>";
?>


	<script>
		function MP_toggle(t){
			//Mostrar u ocultar
			$(t).parent().find(".cuerpo").toggle();
			
			//Marcar como leido
			id=$(t).parent().attr("id");
			ajax_post({
				data : "mp_leido=1&id=" + id,
				retrieve: true
			});
			
		}
		function mp_enviar(t){
			//if(validador()=="form_ok"){
				receptor=$(t).parent().find("input[name='receptor']").val();
				mensaje=$(t).parent().find("textarea[name='mensaje']").val();
				
				if(receptor && mensaje){
					ajax_post({
						data : "mp_enviar=1&receptor=" + receptor + "&mensaje=" + mensaje,
						reload : true
					});
				}
			//}
		}
	</script>







	<?php
		/*require_once("inc/validador.class.php");
		$Validador = new Validador();
		$Validador->SetInput(array('name' => 'receptor', 'alias' => 'Destinatario', 'obligatorio' => 'si'));
		$Validador->SetInput(array('name' => 'mensaje', 'alias' => 'Mensaje', 'formato' => '[A-Za-z0-9]{1,}'));
		$Validador->GeneraValidadorJS();*/
	?>