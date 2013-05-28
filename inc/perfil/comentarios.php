<div class="marco" >
	<h2>Comentarios</h2>
	<script>
		idusuarios = "<?php echo $r_user['idusuarios']; ?>";
		function tablon_leer_comentarios(idusuarios, page) {
			comentarios = ajax_post({
				data : "tablon_leer_comentarios=1&idusuarios="+idusuarios+"&page="+page,
				retrieve : true,
				visible : false
			});
			$("#comentarios").html(comentarios);
		}
		
		function tablon_enviar_comentario() {
			comentario = $("#comentario").val();
			if (comentario) {
				ajax_post({
					data : "tablon_enviar_comentario=1&receptor=" + idusuarios + "&comentario=" + comentario,
					reload : true,
				});
			}
		}

		$(document).ready(function() {
			tablon_leer_comentarios(idusuarios, 1);
		});
	</script>
	<?php
	if($perfil=="ajeno"){
		?>
		<div id="tablon_comentar">
			<textarea id="comentario"></textarea>
			<button type='button' class="azul" onclick="tablon_enviar_comentario()"><span><b>Comentar</b></span></button>
		</div>
		<?php 
	}
	?>
	<div id="comentarios"></div>
</div>