<?php
require ("inc/verify_login.php");

if ($_POST['estado']) {
	mysqli_query($link, "UPDATE usuarios SET estado='" . $_POST['estado'] . "' WHERE idusuarios='" . $global_idusuarios . "'");
	mysqli_query($link, "INSERT INTO novedades (fecha,tipo, propietario,datos) VALUES (now(),'estado','{$global_idusuarios}','{$_POST['estado']}')");
	if (mysqli_errno() == 0) {
		header("Location: perfil.php?estado_cambiado");
	} else {
		error_mysql();
	}
}

if ($_GET['aceptaramistad']) {
	$query = mysqli_query($link, "SELECT * FROM amigos WHERE user1='" . $_GET['emisor'] . "' AND user2='" . $global_idusuarios . "' OR user1='" . $global_idusuarios . "' AND user2='" . $_GET['emisor'] . "'");
	error_mysql("exit");
	if (mysqli_num_rows($query) > 0) {
		header("Location: inicio.php?yasoisamigos");
		error_mysql("exit");
	}

	mysqli_query($link, "INSERT INTO amigos (user1,user2) VALUES ('" . $_GET['emisor'] . "','" . $global_idusuarios . "')");
	mysqli_query($link, "DELETE FROM peticiones WHERE emisor='" . $_GET['emisor'] . "' AND receptor='" . $global_idusuarios . "'");
	mysqli_query($link, "INSERT INTO novedades (fecha,tipo, propietario, visitante) VALUES (now(),'amistad','{$global_idusuarios}','{$_GET['emisor']}')");
	mysqli_query($link, "INSERT INTO novedades (fecha,tipo, visitante,propietario) VALUES (now(),'amistad','{$global_idusuarios}','{$_GET['emisor']}')");
	header("Location: inicio.php");
}

if ($_POST['comentario_tablon'] != "") {
	mysqli_query($link, "INSERT INTO tablon (emisor,receptor,comentario,fecha) VALUES ('" . $global_idusuarios . "','" . $_POST['receptor'] . "','" . $_POST['comentario_tablon'] . "', now())");
	if (mysqli_errno() != 0) {
		error_mysql("exit");
	} else {
		header("Location: gente.php?id=" . $_POST['receptor']);
	}
}

if ($_POST['mensaje_privado'] != "") {
	mysqli_query($link, "INSERT INTO mps (emisor,receptor,mp,fecha) VALUES ('" . $global_idusuarios . "','" . $_POST['receptor'] . "','" . $_POST['mensaje_privado'] . "',now())");
	if (mysqli_errno() != 0) {
		error_mysql("exit");
	} else {
		header("Location: gente.php?id=" . $_POST['receptor'] . "&mp_enviado");
	}
}

if ($_GET['mensaje_leido'] != "") {
	mysqli_query($link, "UPDATE mps SET estado='leido' WHERE idmps='" . $_GET['mensaje_leido'] . "'");
	error_mysql();
}

/************	ALBUM	*********************/
if ($_POST['album']) {
	$result = mysqli_query($link, "SELECT * FROM albums WHERE usuarios_idusuarios='" . $global_idusuarios . "' AND album='" . $_POST['album'] . "'");
	if (mysqli_num_rows($result) > 0) {
		echo "Ya existe un album con el mismo nombre.";
	} else {
		mysqli_query($link, "INSERT INTO albums (usuarios_idusuarios, album) VALUES ('" . $global_idusuarios . "','" . $_POST['album'] . "')");
	}
	die();
}

if ($_POST['album_renombrar']) {
	mysqli_query($link, "UPDATE albums SET album='" . $_POST['album_renombrar'] . "' WHERE idalbums='" . $_POST['album_id'] . "' AND usuarios_idusuarios='" . $global_idusuarios . "'");
	die();
}

if ($_POST['album_borrar']) {
	//$result=mysqli_query($link,"SELECT * FROM albums WHERE usuarios_idusuarios='".$global_idusuarios."' AND album='".$_POST['album']."'");
	/*if(mysqli_num_rows($result)>0){
	 echo "Ya existe un album con el mismo nombre.";
	 }else{
	 mysqli_query($link,"INSERT INTO albums (usuarios_idusuarios, album) VALUES ('".$global_idusuarios."','".$_POST['album']."')");
	 }*/
	mysqli_query($link, "DELETE FROM albums WHERE idalbums='" . $_POST['album_borrar'] . "' AND usuarios_idusuarios='" . $global_idusuarios . "'");
	die();
}

/************	FOTOS	*********************/
if ($_POST['foto_edicion']) {
	mysqli_query($link, "DELETE FROM etiquetas WHERE fotos_idfotos='" . $_POST['idfotos'] . "'");

	preg_match_all("/[0-9]{1,}/", $_POST['etiquetas'], $salida, PREG_PATTERN_ORDER);
	$i2 = 0;
	for ($i = 0; $i < count($salida[0]); $i++) {
		if ($i2 == 0) {
			$id = $salida[0][$i];
			//echo "ID: ".$salida[0][$i]." - ";
		} elseif ($i2 == 1) {
			//echo "X: ".$salida[0][$i]." - ";
			$x = $salida[0][$i];
		} elseif ($i2 == 2) {
			//echo "Y: ".$salida[0][$i]."<br>";
			$y = $salida[0][$i];
			mysqli_query($link, "INSERT INTO etiquetas (fotos_idfotos, usuarios_idusuarios, x, y) VALUES ('" . $_POST['idfotos'] . "','" . $id . "','" . $x . "','" . $y . "')");
			echo mysqli_error($link);
			$i2 = -1;
		}
		$i2++;
	}

	$sql = "UPDATE fotos SET titulo = '" . $_POST['titulo'] . "', albums_idalbums = '" . $_POST['idalbum'] . "' WHERE idfotos='" . $_POST['idfotos'] . "'";
	mysqli_query($link, $sql);

	echo mysqli_error($link);
	die();
}

if ($_POST['foto_principal']) {
	mysqli_query($link, "UPDATE usuarios SET idfotos_princi='" . $_POST['foto_principal'] . "' WHERE idusuarios='" . $global_idusuarios . "'");
	//echo mysqli_error($link);
	mysqli_query($link, "INSERT INTO novedades (fecha, tipo, propietario, datos) VALUES (now(),'foto','{$global_idusuarios}','{$_POST['foto_principal']}')");
	//echo mysqli_error($link);
	die();
}

if ($_GET['foto_borrar']) {
	$result = mysqli_query($link, "SELECT * from fotos WHERE uploader='" . $global_idusuarios . "' AND idfotos='" . $_GET['foto_borrar'] . "'");
	if (mysqli_num_rows($result) == 1) {
		$result = mysqli_fetch_assoc($result);
		unlink($result['archivo']);

		mysqli_query($link, "DELETE FROM fotos WHERE uploader='" . $global_idusuarios . "' AND idfotos='" . $_GET['foto_borrar'] . "'");
	}
	//header("location:fotos.php");
}

if ($_POST['foto_comentario']) {
	mysqli_query($link, "INSERT INTO fotos_comentarios (fotos_idfotos,emisor,comentario,fecha) VALUES ('" . $_POST['idfotos'] . "','" . $global_idusuarios . "','" . $_POST['foto_comentario'] . "',now())");
	header($_SERVER['referer']);
	die();
}

if ($_POST['foto_leer_comentarios']) {
	if ($_POST['page']) {
		$limit = ($_POST['page'] - 1) * 10;
	} else {
		$limit = 0;
	}
	$result = mysqli_query($link, "
				SELECT idusuarios, nombre, apellidos, comentario,
				 DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha2,
				(SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS img_princi
				 FROM fotos_comentarios, usuarios WHERE fotos_idfotos='" . $_POST['idfoto'] . "' AND emisor=idusuarios ORDER BY fecha DESC LIMIT {$limit}, 10");
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			print "<div class='foto_comentario'> 
					 <img src='{$row['img_princi']}' class='imagen_perfil'> 
					 <div> 
						<div> 
							<div class='foto_come_titu'><a href='gente.php?id={$row['idusuarios']}'>{$row['nombre']} {$row['apellidos']}</a></div> 
							<div class='foto_come_fecha'>{$row['fecha2']}</div> 
						</div> 
						<div class='foto_come_men'>{$row['comentario']}</div> 
					</div> 
				</div>";
		}

		//Numeracion paginas
		$q_numeracion = mysqli_query($link, "
			SELECT count(*) AS total
			FROM fotos_comentarios WHERE fotos_idfotos='" . $_POST['idfoto'] . "' ORDER BY fecha DESC");

		$r_numeracion = mysqli_fetch_assoc($q_numeracion);
		$p = $_POST['page'] - 4;
		if ($p < 1)
			$p = 1;

		$actual = $p;
		$siguiente = $_POST['page'] + 1;
		$anterior = $_POST['page'] - 1;
		$u = $_POST['page'] + 4;
		if ($u > ceil($r_numeracion['total'] / 10))
			$u = ceil($r_numeracion['total'] / 10);

		//BARRA NAVEGACION
		echo "<div>";
		if ($_POST['page'] > 1) {
			echo "<div class='flecha_back_top' onclick=\"foto_leer_comentarios(idfoto,0);\"></div>";
			echo "<div class='flecha_back' onclick=\"foto_leer_comentarios(idfoto,{$anterior});\"></div>";
		}
		while ($actual <= $u) {
			echo "<div";
			if ($actual == $_POST['page']) {
				echo " style='font-weight:bold;'";
			}
			echo " onclick='foto_leer_comentarios(idfoto,{$actual});'>{$actual}</div>";

			$actual++;
		}
		//echo "pagina ".$_POST['page']." de ".ceil($r_numeracion['total']/10);
		if ($_POST['page'] < ceil($r_numeracion['total'] / 10)) {
			echo "<div class='flecha_next' onclick=\"foto_leer_comentarios(idfoto,{$siguiente});\"></div>";
			echo "<div class='flecha_next_top' onclick=\"foto_leer_comentarios(idfoto," . ceil($r_numeracion['total'] / 10) . ");\"></div>";
		}

	} else {
		echo "<div>Todavia nadie ha comentado esta foto</div>";
	}
	die();
}

/************	CHAT	*********************/
if ($_POST['chat_estado']) {
	$_SESSION['chat_estado'] = $_POST['chat_estado'];
	mysqli_query($link, "UPDATE usuarios SET chat_estado='{$_POST['chat_estado']}' WHERE idusuarios='" . $global_idusuarios . "'");
	die();
}

if ($_POST['chat_online']) {
	mysqli_query($link, "UPDATE usuarios SET online=now() WHERE idusuarios='" . $global_idusuarios . "'");
}

if ($_POST['chat_enviar']) {
	mysqli_query($link, "INSERT INTO chat (emisor,receptor,mensaje,fecha) VALUES ('" . $global_idusuarios . "','" . $_POST['receptor'] . "','" . $_POST['mensaje'] . "',now())");
}

if ($_POST['chat_leer']) {
	$result = mysqli_query($link, "SELECT *,(SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS archivo from chat,usuarios WHERE  idusuarios=emisor AND receptor='" . $global_idusuarios . "' AND chat.estado='nuevo'");

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<div iduser='" . $row['emisor'] . "' nombre='" . $row['nombre'] . " " . $row['apellidos'] . "' img='" . $row['archivo'] . "' >" . $row['nombre'] . " dijo: " . $row['mensaje'] . "</div>";
		}
	}
	mysqli_query($link, "UPDATE chat SET estado='leido' WHERE receptor='" . $global_idusuarios . "'");
	die();
}

if ($_POST['chat_contactos']) {
	if ($_SESSION['chat_estado'] == "on") {
		//CONECTADOS
		$result = mysqli_query($link, "
								SELECT *,(@tiempo:=TIME_TO_SEC(TIMEDIFF(now(),online))) AS segundos_off,
								CASE
								WHEN @tiempo<60 THEN 'conectado'
								WHEN @tiempo<86000 THEN TIME_FORMAT(TIMEDIFF(now(),online), '%H:%i:%s')
								ELSE DATE_FORMAT(online, '%d/%m/%Y %H:%i') END AS online,
								(SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS archivo
								FROM amigos, usuarios
								WHERE TIME_TO_SEC(TIMEDIFF(now(),online))<95 AND
								(user1='" . $global_idusuarios . "' AND user2=idusuarios OR user2='" . $global_idusuarios . "' AND user1=idusuarios)
								ORDER BY nombre
			");
		if(mysqli_num_rows($result)>0){
			echo "<ul>";
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<li onclick=\"chat_conv_init('" . $row['idusuarios'] . "','" . $row['nombre'] . " " . $row['apellidos'] . "','" . $row['archivo'] . "')\"><div class='conectado'></div>" . $row['nombre'] . " " . $row['apellidos'] . "</li>";
			}
			echo "</ul>";
		}
		//	NO CONECTADOS
		$result = mysqli_query($link, "
								SELECT *,(@tiempo:=TIME_TO_SEC(TIMEDIFF(now(),online))) AS segundos_off,
								CASE
								WHEN @tiempo<60 THEN 'conectado'
								WHEN @tiempo<86000 THEN TIME_FORMAT(TIMEDIFF(now(),online), '%H:%i:%s')
								ELSE DATE_FORMAT(online, '%d/%m/%Y %H:%i') END AS online,
								(SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS archivo
								FROM amigos, usuarios
								WHERE TIME_TO_SEC(TIMEDIFF(now(),online))>95 AND
								(user1='" . $global_idusuarios . "' AND user2=idusuarios OR user2='" . $global_idusuarios . "' AND user1=idusuarios)
								ORDER BY nombre
			");
		echo "<ul>";
		if(mysqli_num_rows($result)>0){
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<li onclick=\"chat_conv_init('" . $row['idusuarios'] . "','" . $row['nombre'] . " " . $row['apellidos'] . "','" . $row['archivo'] . "')\"><div class='desconectado'></div>" . $row['nombre'] . " " . $row['apellidos'] . "</li>";
			}
			echo "</ul>";
		}
		//echo "<p style='cursor:pointer;' onclick=\"chat_turn('off')\">Desactivar Chat</p>";
	} else {
		//echo "<p style='cursor:pointer;' onclick=\"chat_turn('on')\">Activar Chat</p>";
	}
	die();
}
/*
 if($_POST['chat_conv_new']){
 $result=mysqli_query($link,"
 SELECT *,(@tiempo:=TIME_TO_SEC(TIMEDIFF(now(),online))) AS segundos_off,
 CASE
 WHEN @tiempo<60 THEN 'conectado'
 WHEN @tiempo<86000 THEN TIME_FORMAT(TIMEDIFF(now(),online), '%H:%i:%s')
 ELSE DATE_FORMAT(online, '%d/%m/%Y %H:%i') END AS online,
 (SELECT archivo FROM fotos WHERE idfotos=idfotos_princi) AS archivo
 FROM amigos, usuarios
 WHERE (user1='".$global_idusuarios."' AND user2='".$_POST['chat_conv_new']."' OR user2='".$global_idusuarios."' AND user1='".$_POST['chat_conv_new']."')
 AND idusuarios='".$_POST['chat_conv_new']."'
 ");
 $row=mysqli_fetch_assoc($result);
 print(
 "<div id='chat_conv_".$row['idusuarios']."_min' class='chat_ventana_min' onclick=\"max('".$row['idusuarios']."')\">
 &nbsp;<img src='".$row['archivo']."' alt='".$row['nombre']."' />&nbsp;
 <div class='mensajes'></div>
 </div>
 <div id='chat_conv_".$row['idusuarios']."' class='chat_ventana'>
 ".$row['nombre']." ".$row['apellidos']."
 <div class='boton cerr' onclick=\"cerrar('".$row['idusuarios']."')\"></div>
 <div class='boton max'></div>
 <div class='boton mini' onclick=\"mini('".$row['idusuarios']."')\"></div>
 <div id='mensajes'></div>
 <textarea name='mensaje' onkeypress=\"chat_press_enter(event,this,'".$row['idusuarios']."')\" /></textarea>
 <button type='button' onclick=\"enviar('".$row['idusuarios']."')\">Enviar</button>
 </div>");
 die();
 }
 */
/*echo "<pre>";
 if($_POST){
 echo "POST:<br>";
 print_r($_POST);
 echo "<br>";
 }

 if($_GET){
 echo "GET:<br>";
 print_r($_GET);
 }
 echo "</pre>";*/
echo "ERROR";
?>