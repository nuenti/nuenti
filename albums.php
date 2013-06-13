<?php
require ("inc/verify_login.php");
head("Albums");
echo "<body id='seccion_albums'>";
require ("inc/estructura.inc.php");
?>
<div class="barra_full">
	<div class="marco">
	<?php

	//Albums de fotos de ¿?
	if (!$_GET['iduser']) {
		$_GET['iduser'] = $global_idusuarios;
		$user = 'yo';
		echo "<h2 style='margin-bottom:0px;'>Mis Albums de imagenes</h2>";
	} else {
		$usuario = mysqli_query($link,"SELECT * FROM usuarios WHERE idusuarios='" . $_GET['iduser'] . "'");
		if (mysqli_num_rows($usuario) > 0) {
			$row = mysqli_fetch_assoc($usuario);
			echo "<h2 style='margin-bottom:0px;'>Albums de imagenes de " . $row['nombre'] . "</h2>";
		}
	}

	//Subidas
	echo "<div class='album'><div class='album_titulo'><a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=subidas'>Fotos subidas</a></div>";
	$fotos = mysqli_query($link,"SELECT * FROM fotos WHERE uploader='" . $_GET['iduser'] . "' ORDER BY idfotos DESC LIMIT 3");
	if (mysqli_num_rows($fotos)) {
		$bottom = 0;
		$left = 0;
		while ($row = mysqli_fetch_assoc($fotos)) {
			echo "<a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=subidas'><img class='album_cubierta' style='bottom:" . $bottom . "px;left:" . $left . "px;max-width:" . $width . "px;max-height:" . $height . "px;' alt='cubierta album' src='" . $row['archivo'] . "' /><br></a><br>";

			$bottom = $bottom + 90;
			$left = $left + 90;
		}
	} else {
		echo "No has subido ninguna foto";
	}
	echo "</div>";

	//Etiquetadas
	echo "<div class='album'><div class='album_titulo'><a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=etiquetadas'>Fotos etiquetadas</a></div>";
	$fotos = mysqli_query($link,"SELECT * FROM fotos, etiquetas WHERE usuarios_idusuarios = '" . $_GET['iduser'] . "' AND idfotos = fotos_idfotos ORDER BY idfotos DESC LIMIT 3");
	if (mysqli_num_rows($fotos)) {
		$bottom = 0;
		$left = 0;
		while ($row = mysqli_fetch_assoc($fotos)) {
			echo "<a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=etiquetadas'><img class='album_cubierta' style='bottom:" . $bottom . "px;left:" . $left . "px;max-width:" . $width . "px;max-height:" . $height . "px;' alt='cubierta album' src='" . $row['archivo'] . "' /></a><br>";
			$bottom = $bottom + 90;
			$left = $left + 90;
		}
	} else {
		echo "No estas etiquetado en ninguna foto";
	}
	echo "</div>";

	//Personalizados
	echo "<h2 style='clear: both;margin-bottom:0px;'>Albums personales</h2>";

	$personalizados = mysqli_query($link,"SELECT * FROM `albums` WHERE usuarios_idusuarios='" . $_GET['iduser'] . "'");
	if (mysqli_num_rows($personalizados) > 0) {
		while ($row = mysqli_fetch_assoc($personalizados)) {
			print("<div class='album'>
					<div class='album_titulo'>
						<a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=" . $row['idalbums'] . "'>" . $row['album'] . "</a>
						<div class='album_renombrar' onclick=\"album_renombrar('" . $row['idalbums'] . "','" . $row['album'] . "')\"></div>
						<div class='album_borrar' onclick=\"album_borrar('" . $row['idalbums'] . "','" . $row['album'] . "')\"></div>
					</div>");
			$fotos = mysqli_query($link,"SELECT * FROM fotos WHERE albums_idalbums = '" . $row['idalbums'] . "' ORDER BY idfotos LIMIT 3");
			if (mysqli_num_rows($fotos)) {
				$bottom = 0;
				$left = 0;
				while ($row2 = mysqli_fetch_assoc($fotos)) {
					echo "<a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=" . $row['idalbums'] . "'><img class='album_cubierta' style='bottom:" . $bottom . "px;left:" . $left . "px;max-width:" . $width . "px;max-height:" . $height . "px;' alt='cubierta album' src='" . $row2['archivo'] . "' /></a><br>";
					$bottom = $bottom + 90;
					$left = $left + 90;
				}
			} else {
				echo "No hay ninguna foto en este album";
			}
			echo "</div>";
		}
	}

	//Formulario creacion de albumes
	?>
	<div style='display: inline-block;margin: 25px;'>

	Crea un album personalizado
	<hr>
	<div class="input">
		<span>
			<input name="album" id="album_id" type="text" placeholder="Nombre del album">
		</span>
	</div>
	<button type='button' class="azul" onclick="album_crear()"><span><b>Crear album</b></span></button>
	</div>
	</div>

	<script>
		$(document).ready(function() {
			// Muestra y oculta los menús
			$('.album').hover(function(e) {
				$(this).find('.album_renombrar,.album_borrar').css("visibility", "visible");
			}, function(e) {
				$(this).find('.album_renombrar,.album_borrar').css("visibility", "hidden");
			});
		});

		function album_crear() {
			var name = $("input[name='album']").val();
			if (name != null && name != "") {
				ajax_post({
					data:'album='+name,
					reload:true
				});
			}

		}
		
		function album_renombrar(id, name) {
			var name = prompt("Escribe el nombre del album", name);
			if (name != null && name != "") {
				ajax_post({data:'album_renombrar=' + name + '&album_id=' + id,reload: true});
			}
		}

		function album_borrar(id, name) {
			var r = confirm("¿Estás seguro de borrar el album \"" + name + "\" ?");
			if (r == true && id != "") {
				ajax_post({data:'album_borrar=' + id,reload: true});
			}
		}
	</script>
</div>

<?php
require ("inc/chat.php");
?>