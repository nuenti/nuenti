<?php
	//TODO: Pulir diseño y revisar funcionamiento a fondo
	require("inc/verify_login.php");
	head("Mensajeria Privada - Social");
	require("inc/estructura.inc.php");

	print "<div class='barra_full'>
			<div class='marco'>
				<h2>Mensajeria Privada: Enviados</h2>";

	$sql = "SELECT *, DATE_FORMAT(mps.fecha, '%d/%m/%Y %H:%i') AS fechaf FROM mps,usuarios LEFT JOIN fotos ON idfotos=idfotos_princi WHERE emisor='".$global_idusuarios."' AND idusuarios=receptor ORDER BY idmps DESC";
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
					
				<div class='45'>
					<div class='45'>
						<div class='nombre'>{$nombre_apellido}</div>
						<div class='resumen'>{$resumen_mp}</div>
						<div class='fecha'>{$r_mensajes['fechaf']}</div>
					</div><br>
					<div class='texto'>{$r_mensajes['mp']}</div>
					</div>
				</div>
			</div>
		";
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
	</script>