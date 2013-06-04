<div class="barra_der">
	<div class="marco_small">
		<div id="opciones"  onclick="fotos_opciones()">
			<div id="opciones_marco" class="marco_small">
				<button type='button' class="azul" onclick="etiqueta_editar()"><span><b>Editar</b></span></button>
				<button type='button' class="azul" onclick="foto_principal()"><span><b>Principal</b></span></button>
				<button type='button' class="rojo" onclick="foto_borrar()"><span><b>Borrar</b></span></button>
			</div>
		</div>
		
		
	<?php 
		echo NombreApellido($r_fotos['nombre'].' '.$r_fotos['apellidos'], 26)."<br>";
		echo strftime ("%A %#d de %B del %y", strtotime($r_fotos['fecha']))."<br>";	
	 ?>
	 
		<div id="foto_album">
			<div class="original">
				Album
				<?php
				if ($r_fotos['album']) {
					echo $r_fotos['album'];
				} else {
					echo $_GET['idalbum'];
				}
				echo "</div>";
				echo "<div class='edicion'>";
				$albums = mysqli_query($link, "SELECT * FROM `albums` WHERE usuarios_idusuarios='" . $_GET['iduser'] . "'");
				print "Album: <div class='input'>
								<span class='select'>
									<select name='foto_album'><option value=''>Ninguno</option>";
				if (mysqli_num_rows($albums) > 0) {
					while ($row = mysqli_fetch_assoc($albums)) {
						echo "<option value='" . $row['idalbums'] . "'";
						if ($r_fotos['albums_idalbums'] == $row['idalbums'])
							echo " selected";
						echo ">" . $row['album'] . "</option>";
					}
				} else {
		
				}
				?>
					</select>
				</span>
			</div>
		</div>
	</div>
	</div>
	
	<div class="marco_small">
	<ul id="lista_etiquetados" style='margin:0;list-style: none outside none;padding:0px;'>
		<div id="nadie">No hay nadie etiquetado</div>
		<?php
		if (mysqli_num_rows($q_fotos)) {
			if (mysqli_num_rows($etiquetados) > 0) {
				mysqli_data_seek($etiquetados, 0);
				while ($etiqueta = mysqli_fetch_assoc($etiquetados)) {
					print "<li class='etiqueta_".$etiqueta['idusuarios']."'>
						<img src='{$etiqueta['archivo']}' class='autocomplete_img'>
						".NombreApellido($etiqueta['nombre']." ".$etiqueta['apellidos'])."
						<div onclick=\"etiqueta_borrar('" . NombreApellido($etiqueta['nombre'] . " " . $etiqueta['apellidos']) . "','" . $etiqueta['idusuarios'] . "','" . $etiqueta['archivo'] . "')\">
						</div>
						</li>";
				}
			}
		}
		?>
	</ul>
	<?php
	$query=mysqli_query($link,"
			SELECT idusuarios, nombre, apellidos, archivo
			FROM amigos, usuarios
			LEFT JOIN fotos
			ON idfotos_princi=idfotos
			WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR user2='".$global_idusuarios."' AND user1=idusuarios
			UNION
			SELECT idusuarios, nombre, apellidos, archivo
			FROM usuarios
			LEFT JOIN fotos
			ON idfotos_princi=idfotos
			WHERE idusuarios='" . $global_idusuarios . "'
		");
	?>
	<script>
		//Declarando variables
		lista_amigos = new Array();
		lista_etiquetados = [<?php
		if (mysqli_num_rows($q_fotos)) {
			if (mysqli_num_rows($etiquetados) > 0) {
				mysqli_data_seek($etiquetados, 0);
				$i_temp = 0;
				while ($etiqueta = mysqli_fetch_assoc($etiquetados)) {
					if ($i_temp != 0)
						echo ",";
					print "{
						value: " . $etiqueta['idusuarios'] . ",
						label: '" . NombreApellido($etiqueta['nombre'] . " " . $etiqueta['apellidos']) . "',
						 x: " . $etiqueta['x'] . ",
						 y: " . $etiqueta['y'] . "
					}";
					$i_temp++;
				}
			}
		}
		?>
			];
		idfoto = <?php echo $r_fotos['idfotos']; ?>;
		var lista_amigos = [
		<?php
		$i_temp = 0;
		while ($row = mysqli_fetch_assoc($query)) {
			if ($i_temp != 0)
				echo ",";
			echo "{
				value: '" . $row['idusuarios'] . "',
				label: '" . NombreApellido($row['nombre'] . " " . $row['apellidos']) . "',
				icon: '".$row['archivo']."'
			}";
			$i_temp++;
		}
		?>
	];
	</script>

	<div class="edicion amigo">
		<div class='input'>
			<span>
				<input id='amigo' type='text' autocomplete='off' placeholder='Nombre de amigo'>
			</span>
		</div>
	</div>
	<button type='button' class="azul edicion" onclick="fotos_post()" style="display: none !important;"><span><b>Guardar</b></span></button>
	<button type='button' class="azul edicion" onclick="foto_cancelar_edicion();" style="display: none !important;"><span><b>Cancelar</b></span></button>
</div>
</form>
</div>