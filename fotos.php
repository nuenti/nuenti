<?php
require ("inc/verify_login.php");
head("Fotos - Social");
echo "<script type='text/javascript' src='fotos/foto_etiqueta.js'></script>";
echo "<script type='text/javascript' src='fotos/foto_visualizador.js'></script>";
require ("inc/estructura.php");

if (!$_GET['iduser']) {
	$_GET['iduser'] = $global_idusuarios;
	$uploader = 1;
}

if (!$_GET['idalbum']) {
	die("no idalbum");
}
if (!$_GET['idfotos']) {
	$_GET['idfotos'] = "999999999";
}

if ($_GET['idalbum'] == 'subidas') {
	//$fotos = mysqli_query($link,"SELECT * FROM fotos LEFT JOIN albums ON albums_idalbums = idalbums WHERE uploader='" . $_GET['iduser'] . "' AND idfotos<='" . $_GET['idfotos'] . "' ORDER BY idfotos DESC LIMIT 2");
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS ultima,
				(SELECT MAX(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS primera,
				(SELECT MIN(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "') AS totales,
				(SELECT @totales - COUNT(idfotos) FROM fotos WHERE uploader = '" . $_GET['iduser'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos
			WHERE idfotos='" . $_GET['idfotos'] . "'";
	$fotos = mysqli_query($link,$query);
	$row_fotos = mysqli_fetch_assoc($fotos);
} elseif ($_GET['idalbum'] == 'etiquetadas') {
	//$fotos = mysqli_query($link,"SELECT * FROM etiquetas, fotos LEFT JOIN albums ON idalbums = albums_idalbums WHERE etiquetas.usuarios_idusuarios = '" . $_GET['iduser'] . "' AND idfotos = fotos_idfotos AND idfotos <= '" . $_GET['idfotos'] . "' ORDER BY idfotos DESC LIMIT 2");
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS ultima,
				(SELECT MAX(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS primera,
				(SELECT MIN(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos) AS totales,
				(SELECT @totales - COUNT(idfotos)  FROM fotos, etiquetas WHERE usuarios_idusuarios='" . $_GET['iduser'] . "' AND idfotos=fotos_idfotos AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos
			LEFT JOIN albums ON albums_idalbums=idalbums
			WHERE idfotos='" . $_GET['idfotos'] . "'";
			
	$fotos = mysqli_query($link,$query);
	$row_fotos = mysqli_fetch_assoc($fotos);
} else {
	$query = "SELECT  *,
				(SELECT MIN(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "') AS ultima,
				(SELECT MAX(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' ) AS primera,
				(SELECT MIN(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos>'" . $_GET['idfotos'] . "') AS anterior,
				(SELECT MAX(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS siguiente,
				@totales:=(SELECT COUNT(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "') AS totales,
				(SELECT @totales - COUNT(idfotos) FROM fotos WHERE albums_idalbums='" . $_GET['idalbum'] . "' AND idfotos<'" . $_GET['idfotos'] . "') AS actual
			FROM fotos, albums
			WHERE idfotos='" . $_GET['idfotos'] . "' AND albums_idalbums='" . $_GET['idalbum'] . "' AND albums_idalbums=idalbums";
	$fotos = mysqli_query($link,$query);
	$row_fotos = mysqli_fetch_assoc($fotos);
	/*$foto = mysqli_query($link,"SELECT * FROM albums, fotos LEFT JOIN etiquetas ON idfotos = fotos_idfotos WHERE albums_idalbums = '" . $_GET['idalbum'] . "'  AND idalbums = albums_idalbums AND idfotos = '" . $_GET['idfotos'] . "'");
	 $foto_siguiente = mysqli_query($link,"SELECT idfotos FROM fotos WHERE albums_idalbums = '" . $_GET['idalbum'] . "' AND idfotos < '" . $_GET['idfotos'] . "' ORDER BY idfotos DESC LIMIT 1");
	 $foto_n = mysqli_query($link,"SELECT count(idfotos)+1 AS nfotos, idfotos FROM fotos WHERE albums_idalbums = '" . $_GET['idalbum'] . "' AND idfotos > '" . $_GET['idfotos'] . "' ORDER BY idfotos DESC");
	 $foto_total = mysqli_query($link,"SELECT count(idfotos) AS nfotos FROM fotos WHERE albums_idalbums = '" . $_GET['idalbum'] . "'");*/
}

/*if($_GET['album']=='subidas'){
 $permiso_foto="dueño";
 $fotos=mysqli_query($link,"SELECT * FROM fotos WHERE uploader='".$_GET['user']."' AND idfotos<='".$_GET['idfotos']."' ORDER BY idfotos DESC LIMIT 2");
 }elseif($_GET['album']=='etiquetadas'){
 $fotos=mysqli_query($link,"SELECT * FROM fotos, etiquetas WHERE usuarios_idusuarios = '".$global_idusuarios."' AND idfotos = fotos_idfotos AND idfotos<='".$_GET['idfotos']."' ORDER BY idfotos DESC LIMIT 2");
 if(mysqli_num_rows($fotos)){
 $t=mysqli_fetch_assoc($fotos);
 if($t['uploader']==$global_idusuarios){
 $permiso_foto=="dueño";
 }else{
 $permiso_foto=="etiquetado";
 }
 mysqli_data_seek($fotos, 0);
 }
 }elseif($_GET['album']=='perfil'){
 $permiso_foto=="dueño";
 // TODO: Fotos del perfil
 $fotos=mysqli_query($link,"");
 }elseif($_GET['album']){

 }else{
 die("Error 1075");
 }*/
require ("fotos/foto.php");
require ("fotos/barra.php");
require ("fotos/comentarios.php");
?>