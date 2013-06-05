<?php
	echo "<h2>Mensajeria Privada: Recibidos</h2>";
	echo "<div id='mps_separador'></div>";
	
	$sql = "SELECT *, DATE_FORMAT(mps.fecha, '%W %e de %M a las %H:%i') AS fechaf FROM mps,usuarios LEFT JOIN fotos ON idfotos=idfotos_princi WHERE receptor='".$global_idusuarios."' AND idusuarios=emisor ORDER BY idmps DESC";
	$q_mensajes = mysqli_query($link,$sql);
	
	if(mysqli_num_rows($q_mensajes)>0){
		while($r_mensajes=mysqli_fetch_assoc($q_mensajes)){
			
			$nombre_apellido = $r_mensajes['nombre']." ".$r_mensajes['apellidos'];
			
			if(strlen($r_mensajes['mp'])>20){
				$resumen_mp = substr($r_mensajes['mp'], 0, 20)."...";
			}else{
				$resumen_mp = $r_mensajes['mp'];
			}
			
			if($r_mensajes['leido'] == 0){
				$nuevo = " nuevo";
			}else{
				$nuevo = "";
			}
			
			 print "
				<div id='{$r_mensajes['idmps']}' class='mensaje{$nuevo}'>
					<div class='encabezado' onclick='MP_toggle(this)'>
						<div class='img'>
							<a href='perfil.php?id={$r_mensajes['emisor']}'>
								<img src='{$r_mensajes['archivo']}'>
							</a>
						</div>
						<div class='datos'>
							<div class='nombre'><a href='perfil.php?id={$r_mensajes['emisor']}'>{$nombre_apellido}</a></div>
							<div class='fecha'>".utf8_encode($r_mensajes['fechaf'])."</div><br>
							<div class='resumen'>{$resumen_mp}</div>
						</div>
					</div>
					<div class='cuerpo'>
						<div class='texto'>{$r_mensajes['mp']}</div>
						<form action='#' onsubmit='return false'>
							<input type='hidden' name='receptor' value='{$r_mensajes['emisor']}'>
							<div class='input'>
								<span>
									<textarea name='mensaje' class='validable' placeholder='Escribe aqui tu respuesta'></textarea>
								</span>
							</div>
							<button type='button' class='azul' onclick='mp_enviar(this);'><span><b>Responder</b></span></button>
						</form>
					</div>
				</div>
			";
		}
	}else{
		echo "<div id='vacia'>No tienes ningun mensaje en tu bandeja</div>";
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
			
			$(t).parent().removeClass("nuevo");
		}
		function mp_enviar(t){
				receptor=$(t).parent().find("input[name='receptor']").val();
				mensaje=$(t).parent().find("textarea[name='mensaje']").val();
				
				if(receptor && mensaje){
					ajax_post({
						data : "mp_enviar=1&receptor=" + receptor + "&mensaje=" + mensaje,
						reload : true
					});
				}
		}
	</script>