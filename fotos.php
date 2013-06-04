<?php
require ("inc/verify_login.php");
head("Fotos - Social");
echo "<script type='text/javascript' src='jscripts/foto_edicion.js'></script>";
echo "<script type='text/javascript' src='jscripts/foto_visualizador.js'></script>";
echo "<body id='seccion_fotos'>";
require ("inc/estructura.inc.php");

if (!$_GET['iduser']) {
	$_GET['iduser'] = $global_idusuarios;
	//$uploader = 1;
}

if (!$_GET['idalbum']) {
	die("no idalbum");
}
if (!$_GET['idfotos']) {
	$_GET['idfotos'] = "999999999";
}

if ($_GET['idalbum'] == 'subidas') {
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS ultima,
				(SELECT MAX(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS primera,
				(SELECT MIN(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS totales,
				(SELECT @totales - COUNT(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos
			LEFT JOIN albums ON albums_idalbums=idalbums
			LEFT JOIN usuarios ON uploader=idusuarios
			WHERE idfotos='" . $_GET['idfotos'] . "'";
	$q_fotos = mysqli_query($link,$query);
	$r_fotos = mysqli_fetch_assoc($q_fotos);
	//print_r($r_fotos);
} elseif ($_GET['idalbum'] == 'etiquetadas') {
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS ultima,
				(SELECT MAX(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS primera,
				(SELECT MIN(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS totales,
				(SELECT @totales - COUNT(idfotos)  FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos
			LEFT JOIN albums ON albums_idalbums=idalbums
			LEFT JOIN usuarios ON uploader=idusuarios
			WHERE idfotos='" . $_GET['idfotos'] . "'";
			
	$q_fotos = mysqli_query($link,$query);
	$r_fotos = mysqli_fetch_assoc($q_fotos);
} else {
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "') AS ultima,
				(SELECT MAX(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' ) AS primera,
				(SELECT MIN(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "') AS totales,
				(SELECT @totales - COUNT(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos, albums
			LEFT JOIN albums ON albums_idalbums=idalbums
			LEFT JOIN usuarios ON uploader=idusuarios
			WHERE idfotos='" . $_GET['idfotos'] . "' AND albums_idalbums='" . $_GET['idalbum'] . "' AND albums_idalbums=idalbums";
	$q_fotos = mysqli_query($link,$query);
	$r_fotos = mysqli_fetch_assoc($q_fotos);
}
require ("inc/fotos/main.inc.php");
require ("inc/fotos/barra.inc.php");
require ("inc/fotos/comentarios.inc.php");
?>