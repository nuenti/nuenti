<?php
	if(mysqli_num_rows($fotos)){

?>
<div class="barra_full">
	<div class="marco">
		<textarea name="foto_comentario" id="foto_comentario_mens" cols="60" rows="2" placeholder="Escribe aqu&iacute; tu comentario" style="max-width: 826px;vertical-align: top;"></textarea>
		<button onclick="enviar_comentario()">
			Comentar
		</button>
		<div id="comentarios"></div>
		<script>
					$(window).load(function() {
				foto_leer_comentarios(<?php echo $row_fotos['idfotos']; ?>
				,1);
				});
		</script>
		<?php
		/*$query = mysqli_query($link, "
		 SELECT idusuarios, nombre, apellidos, comentario,
		 DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha,
		 (SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS img_princi
		 FROM fotos_comentarios, usuarios WHERE fotos_idfotos='" . $row_fotos['idfotos'] . "' AND emisor=idusuarios ORDER BY fecha DESC");
		 if (mysqli_num_rows($query) > 0) {
		 echo "<div style='background-color: #E5E5E5;height: 1px;width: 947px;margin-top:10px;'></div>";
		 while ($comentarios = mysqli_fetch_assoc($query)) {
		 echo "<div class='foto_comentario'>";
		 echo "<img src='" . $comentarios['img_princi'] . "'>";
		 echo "<div>";
		 echo "<div>";
		 echo "<div class='foto_come_titu'><a href='gente.php?id=" . $comentarios['idusuarios'] . "'>" . $comentarios['nombre'] . " " . $comentarios['apellidos'] . "</a></div>";
		 echo "<div class='foto_come_fecha'>" . $comentarios['fecha'] . "</div>";
		 echo "</div>";
		 echo "<div class='foto_come_men'>" . $comentarios['comentario'] . "</div>";
		 echo "</div>";
		 echo "</div>";
		 }
		 } else {
		 echo "<div>Todavia nadie ha comentado esta foto</div>";
		 }*/
		?>
	</div>
</div>
<?php
}
?>