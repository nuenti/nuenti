<?php
require ("inc/verify_login.php");
head("Albums - Social");
require ("inc/estructura.inc.php");
?>
<div class="barra_full">
	<?php
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
	
	
	
	
	if (!$_GET['page']) {
		$_GET['page'] = 1;
	}
	
	$limit_min = ($_GET['page'] - 1) * 10;
	$limit_max = ($_GET['page']) * 10;
	$sql_old = $sql;
	$sql = $sql . " LIMIT " . $limit_min . ", " . $limit_max;
	//echo $sql;
	$fotos = mysqli_query($link,$sql);
	
	if (mysqli_num_rows($fotos)) {
		$row = mysqli_fetch_assoc($fotos);
		
		//ALBUM etiquetadas o subidas
		if ($_GET['idalbum'] == "subidas") {
			echo "<h2 style='margin-bottom:0px;'>Fotos subidas por ".$row['nombre']."</h2>";
		}elseif ($_GET['idalbum'] == "etiquetadas") {
			echo "<h2 style='margin-bottom:0px;'>Fotos etiquetadas de ".$row['nombre']."</h2>";
		}else{
			echo "<h2 style='margin-bottom:0px;'>Fotos del album ".$row['album']." de ".$row['nombre']."</h2>";
		}
		mysqli_data_seek($fotos, 0);
		while ($row = mysqli_fetch_assoc($fotos)) {
			echo "<a href='fotos.php?iduser=" . $row['idusuarios'] . "&idalbum=" . $_GET['idalbum'] . "&idfotos=" . $row['idfotos'] . "' class='album_contenido'><img class='album_contenido' style='' alt='' src='" . $row['archivo'] . "' />
	<br>
	</a>";
		}
	} else {
		echo "No has subido ninguna foto";
	}
	
	//Numeracion de paginas
	$result = mysqli_query($link,$sql_old);
	$npage_max = ceil(mysqli_num_rows($result) / 10);
	$npage_initial = $_GET['page'] - 5;
	if ($npage_initial < 1)
		$npage_initial = 1;
	
	
	//BARRA NAVEGACION
		echo "<div>";
		if($row_fotos['actual']>1){
			echo "<div class='flecha_back_top' onclick=\"location.href='".$navegar_primera."'\"></div>";
			echo "<div class='flecha_back' onclick=\"location.href='".$navegar_anterior."'\"></div>";
		}
		echo "Pagina ".$row_fotos['actual']." de ".$row_fotos['totales'];
		if($row_fotos['actual']<$row_fotos['totales']){
			echo "<div class='flecha_next' onclick=\"location.href='".$navegar_siguiente."'\"></div>";
			echo "<div class='flecha_next_top' onclick=\"location.href='".$navegar_ultima."'\"></div>";
		}
		echo "</div>";
	?>
	
	
	
	<div id="barra_navegacion">
		<?php
		for ($i = 0; $i < 10; $i++) {
			if($npage_initial > $npage_max)
				break;
			
			if($npage_initial == $_GET['page']){
				echo $npage_initial;
			}else{
				echo "<a href='album.php?iduser=" . $_GET['iduser'] . "&idalbum=" . $_GET['idalbum'] . "&page=" . $npage_initial . "'>" . $npage_initial . "</a>";
			}
			$npage_initial++;
		}
		?>
	</div>
</div>
