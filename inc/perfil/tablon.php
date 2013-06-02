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
					
		<div class="input">
			<span>
				<input id="estado" name="estado" placeholder="Escribe algo sobre ti" type="text" value="<?php echo $estado; ?>" size='80' maxlength='40' autofocus>
			</span>
		</div>
		<button type='button' class="azul" onclick="estado_cambiar(estado_ori)"><span><b>Cambiar</b></span></button>
				  
	<?php
	}else{
		echo "<div>{$estado}</div>";
	}
	?>
</div>