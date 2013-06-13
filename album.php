<?php
require ("inc/verify_login.php");
head("Albums");
require ("inc/estructura.inc.php");
?>
<div class="barra_full">
	<div class="marco">
		<?php

		##### CONSULTA SQL

		if (!$_GET['page']) {
			$_GET['page'] = 1;
		}
		
		if (!$_GET['idalbum']) {
			die("no idalbum");
		} elseif (($_GET['idalbum'] == "subidas" OR $_GET['idalbum'] == "etiquetadas") AND !$_GET['iduser']) {
			die("no idusuario");
		} elseif (($_GET['idalbum'] == "subidas" OR $_GET['idalbum'] == "etiquetadas") AND $_GET['iduser']) {
			//ALBUM etiquetadas o subidas
			if ($_GET['idalbum'] == "subidas") {
				$sql = "SELECT * FROM usuarios,fotos WHERE idusuarios = uploader AND uploader='" . $_GET['iduser'] . "' ORDER BY idfotos DESC";
			}
			if ($_GET['idalbum'] == "etiquetadas") {
				$sql = "SELECT * FROM usuarios,fotos, etiquetas WHERE usuarios_idusuarios = '" . $_GET['iduser'] . "' AND usuarios_idusuarios = idusuarios AND idfotos = fotos_idfotos ORDER BY idfotos DESC";
			}
		} elseif ($_GET['idalbum']) {
			//Album personalizado
			$sql = "SELECT * FROM usuarios, albums, fotos WHERE idusuarios = usuarios_idusuarios AND idalbums = '" . $_GET['idalbum'] . "' AND idalbums = albums_idalbums  AND idfotos <= '999999999' ORDER BY idfotos DESC";
		}

		$limit_min = ($_GET['page'] - 1) * 10;
		$limit_max = 10;
		$sql_old = $sql;
		$sql = $sql . " LIMIT " . $limit_min . ", " . $limit_max;
		$fotos = mysqli_query($link, $sql);

		if (mysqli_num_rows($fotos)) {
			$row = mysqli_fetch_assoc($fotos);

			##### ENCABEZADO
			if ($_GET['idalbum'] == "subidas") {
				echo "<h2 style='margin-bottom:0px;'>Fotos subidas por " . $row['nombre'] . "</h2>";
			} elseif ($_GET['idalbum'] == "etiquetadas") {
				echo "<h2 style='margin-bottom:0px;'>Fotos etiquetadas de " . $row['nombre'] . "</h2>";
			} else {
				echo "<h2 style='margin-bottom:0px;'>Fotos del album " . $row['album'] . " de " . $row['nombre'] . "</h2>";
			}
			
			##### MOSTRAR FOTOS
			mysqli_data_seek($fotos, 0);
			while ($row = mysqli_fetch_assoc($fotos)) {
				echo "<a href='fotos.php?iduser=" . $row['idusuarios'] . "&idalbum=" . $_GET['idalbum'] . "&idfotos=" . $row['idfotos'] . "' class='album_contenido'><img class='album_contenido' style='' alt='' src='{$row['archivo']}' /><br></a>";
			}
		} else {
			echo "No has subido ninguna foto";
			die();
		}


		###### BARRA NAVEGACION
		$result = mysqli_query($link, $sql_old);
		$npage_max = ceil(mysqli_num_rows($result) / 10);
		$npage_initial = $_GET['page'];
		
		if($npage_initial>1)
			$anterior=$npage_initial-1;
		
		if($npage_initial<$npage_max)
			$siguiente=$npage_initial+1;
		
		$navegar_primera="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&page=1";
		if($anterior)
			$navegar_anterior="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&page=".$anterior;
		if($siguiente)
			$navegar_siguiente="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&page=".$siguiente;
		$navegar_ultima="?iduser=".$_GET['iduser']."&idalbum=".$_GET['idalbum']."&page=".$npage_max;
	
				?>
		<script>
			//Declarando variables
			iduser = "<?php echo $_GET['iduser']; ?>";
			idalbum = "<?php echo $_GET['idalbum']; ?>";
			page = "<?php echo $_GET['page']; ?>";
		</script>
		
		<?php
		echo "<div id='barra_navegacion'>";
		if ($npage_initial > 1) {
			echo "<img class='flecha_back_top' src='css/flechas/flecha_left_top.jpg' onclick=\"location.href='" . $navegar_primera . "'\">";
			echo "<img class='flecha_back' src='css/flechas/flecha_left.jpg' onclick=\"location.href='" . $navegar_anterior . "'\">";
		}
		echo "<div class='texto'>" . $npage_initial . " de " . $npage_max . "</div>";
		if ($npage_initial < $npage_max) {
			echo "<img class='flecha_next' src='css/flechas/flecha_right.jpg' onclick=\"location.href='" . $navegar_siguiente . "'\">";
			echo "<img class='flecha_next_top' src='css/flechas/flecha_right_top.jpg' onclick=\"location.href='" . $navegar_ultima . "'\">";
		}
		echo "</div>";
		?>
		</div>
		</div>
	</div>
