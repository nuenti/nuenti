<div class="barra_der">
	<div id="foto_album">
		<div class="original">
		Album
		<?php
		if ($row_fotos['album']) {
			echo $row_fotos['album'];
		} else {
			echo $_GET['idalbum'];
		}
		echo "</div>";
		echo "<div class='edicion'>";
		$albums = mysqli_query($link, "SELECT * FROM `albums` WHERE usuarios_idusuarios='" . $_GET['iduser'] . "'");
		if (mysqli_num_rows($albums) > 0) {
			echo "Album: <select name='foto_album'><option value='NULL'>Ninguno</option>";
			while ($row = mysqli_fetch_assoc($albums)) {
				echo "<option value='" . $row['idalbums'] . "'";
				if ($row_fotos['albums_idalbums'] == $row['idalbums'])
					echo " selected";
				echo ">" . $row['album'] . "</option>";
			}
			echo "</select>";
		} else {

		}
		?>
		</div>
	</div>

	<!-- info sobre foto (fecha)-->

	<ul style='margin:0;list-style: none outside none;padding:0px;'>
		<li>
			<a href="#" onclick="etiqueta_editar()">Editar</a>
		</li>
		<li>
			<a href="#" onclick="foto_principal()">Principal</a>
		</li>
		<li>
			<a href="post.php?foto_borrar=<?php echo $row_fotos['idfotos']; ?>">Borrar foto</a>
		</li>
	</ul>

	Personas:
	<br>
	<ul id="lista_etiquetados" style='margin:0;list-style: none outside none;padding:0px;'>
		<?php
		if (mysqli_num_rows($fotos)) {
			if (mysqli_num_rows($etiquetados) > 0) {
				mysqli_data_seek($etiquetados, 0);
				while ($personas = mysqli_fetch_assoc($etiquetados)) {
					echo "<li class='etiqueta_" . $personas['idusuarios'] . "'>" . $personas['nombre'] . " " . $personas['apellidos'] . "<div onclick=\"etiqueta_borrar('" . $personas['nombre'] . " " . $personas['apellidos'] . "','" . $personas['idusuarios'] . "')\"></div></li>";
				}
			} else {
				echo "No hay nadie etiquetado todavia";
			}
		}
		?>
	</ul>
	<?php
	$query = mysqli_query($link, "SELECT idusuarios, nombre, apellidos FROM amigos, usuarios WHERE user1='" . $global_idusuarios . "' AND user2=idusuarios OR user2='" . $global_idusuarios . "' AND user1=idusuarios UNION SELECT idusuarios, nombre, apellidos FROM usuarios WHERE idusuarios='" . $global_idusuarios . "'");
	?>
	<script>
		//Declarando variables
lista_amigos = new Array();
lista_etiquetados = [<?php
if (mysqli_num_rows($fotos)) {
	if (mysqli_num_rows($etiquetados) > 0) {
		mysqli_data_seek($etiquetados, 0);
		$i_temp = 0;
		while ($personas = mysqli_fetch_assoc($etiquetados)) {
			if ($i_temp != 0)
				echo ",";
			echo "{value: " . $personas['idusuarios'] . ", label: '" . $personas['nombre'] . " " . $personas['apellidos'] . "', x: " . $personas['x'] . ", y: " . $personas['y'] . "}";
			$i_temp++;
		}
	}
}
?>
	];
idfoto = <?php echo $row_fotos['idfotos']; ?>;
var lista_amigos = [
<?php
$i_temp = 0;
while ($row = mysqli_fetch_assoc($query)) {
	if ($i_temp != 0)
		echo ",";
	echo "{value: '" . $row['idusuarios'] . "', label: '" . $row['nombre'] . " " . $row['apellidos'] . "'}";
	$i_temp++;
}
?>
	];
	</script>

	<div class="ui-widget">
		<label for="tags" style="display: none;">
			<br>
			Amigo: </label>
		<input id="tags" name="receptor"  style="display: none;" placeholder="amigo"/>
	</div>
	<br>

	<button type="button" onclick="fotos_post();" style="display: none !important;" class='edicion'>
		Guardar
	</button>
	<button type="button" onclick="foto_cancelar_edicion();" style="display: none !important;" class='edicion'>
		Cancelar
	</button>
</div>
</form>