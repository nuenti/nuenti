<div class="marco">
	<?php
	if ($r_user['estado']) {
		$estado = $r_user['estado'];
	} else {
		$estado = "Actualiza tu estado";
	}
	
	if($perfil == "propio"){
	?>
		<script>
			function estado_cambiar(estado_ori){
				estado = $("#estado").val();
				if(estado != estado_ori){
					ajax_post({
						data : "estado_cambiar=1&estado=" + estado,
						reload : true
					});
				}//TODO: advertir que el estado no ha cambiado
			}
			$(document).ready(function(){
				estado_ori = $("#estado").val();
			});
		</script>
		<input type='text' id='estado' size='110' value='<?php echo $r_user['estado']; ?>' />
		<button type='button' class="azul" onclick="estado_cambiar(estado_ori)"><span><b>Cambiar</b></span></button>
				  
	<?php
	}else{
		echo "<div>{$estado}</div>";
	}
	?>
</div>