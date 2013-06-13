<div class="marco_small">
	<h2>Albums de imagenes de <?php echo $r_user['nombre']; ?></h2>
	<?php
	
	//Subidas
	echo "<div class='album'><div class='album_titulo'><a href='album.php?iduser=" . $_GET['id'] . "&idalbum=subidas'>Fotos subidas</a></div>";
	$fotos = mysqli_query($link,"SELECT * FROM fotos WHERE uploader='" . $_GET['id'] . "' ORDER BY idfotos DESC LIMIT 3");
	if (mysqli_num_rows($fotos)) {
		while ($row = mysqli_fetch_assoc($fotos)) {
			echo "<div class='foto'><a href='album.php?iduser=" . $_GET['id'] . "&idalbum=subidas'><img class='album_cubierta' alt='cubierta album' src='" . $row['archivo'] . "' /></a></div>";
		}
	} else {
		echo "No ha subido nignuna foto";
	}
	echo "</div>";

	//Etiquetadas
	echo "<div class='album'><div class='album_titulo'><a href='album.php?iduser=" . $_GET['id'] . "&idalbum=etiquetadas'>Fotos etiquetadas</a></div>";
	$fotos = mysqli_query($link,"SELECT * FROM fotos, etiquetas WHERE usuarios_idusuarios = '" . $_GET['id'] . "' AND idfotos = fotos_idfotos ORDER BY idfotos DESC LIMIT 3");
	if (mysqli_num_rows($fotos)) {
		while ($row = mysqli_fetch_assoc($fotos)) {
			echo "<div class='foto'><a href='album.php?iduser=" . $_GET['id'] . "&idalbum=etiquetadas'><img class='album_cubierta' alt='cubierta album' src='" . $row['archivo'] . "' /></a></div>";
		}
	} else {
		echo "No esta etiquetado en ninguna foto";
	}
	echo "</div>";

	//Personalizados
	$personalizados = mysqli_query($link,"SELECT * FROM `albums` WHERE usuarios_idusuarios='" . $_GET['id'] . "'");
	if (mysqli_num_rows($personalizados) > 0) {
		echo "<h2 style='clear: both;margin-bottom:0px;'>Albums personales</h2>";
		while ($row = mysqli_fetch_assoc($personalizados)) {
			print("<div class='album'>
					<div class='album_titulo'>
						<a href='album.php?iduser=" . $_GET['id'] . "&idalbum=" . $row['idalbums'] . "'>" . $row['album'] . "</a>
						<div class='album_renombrar' onclick=\"album_renombrar('" . $row['idalbums'] . "','" . $row['album'] . "')\"></div>
						<div class='album_borrar' onclick=\"album_borrar('" . $row['idalbums'] . "','" . $row['album'] . "')\"></div>
					</div>");
			$fotos = mysqli_query($link,"SELECT * FROM fotos WHERE albums_idalbums = '" . $row['idalbums'] . "' ORDER BY idfotos LIMIT 3");
			if (mysqli_num_rows($fotos)) {
				while ($row2 = mysqli_fetch_assoc($fotos)) {
					echo "<div class='foto'><a href='album.php?iduser=" . $_GET['id'] . "&idalbum=" . $row['idalbums'] . "'><img class='album_cubierta' style='bottom:" . $bottom . "px;left:" . $left . "px;max-width:" . $width . "px;max-height:" . $height . "px;' alt='cubierta album' src='" . $row2['archivo'] . "' /></a></div>";
				}
			} else {
				echo "No tiene ninguna foto en este album";
			}
			echo "</div>";
		}
	}
	?>
	
	
	
</div>