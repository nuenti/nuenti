<?php
	if(mysqli_num_rows($q_fotos)){
?>
<div class="barra_izq_centro">
	<div class="marco">
		<div class="input">
			<span>
				<textarea name="foto_comentario" id="foto_comentario_mens" cols="60" rows="2" placeholder="Escribe aqu&iacute; tu comentario" style="width: 650px;height: 60px;resize: none;"></textarea>
			</span>
		</div>
		<button type='button' class="azul" onclick="enviar_comentario()">
			<span><b>Comentar</b></span>
		</button>
		<div id="comentarios"></div>
		<script>
			$(window).load(function() {
				foto_leer_comentarios(<?php echo $r_fotos['idfotos']; ?>,1);
			});
		</script>
	</div>
</div>
<?php
}
?>