<?php
require ("inc/verify_login.php");

########	PERFIL

if ($_POST['estado_cambiar']) {
	mysqli_query($link, "UPDATE usuarios SET estado='" . $_POST['estado'] . "' WHERE idusuarios='" . $global_idusuarios . "'");
	
	// Novedad
	$novedad = array("propietario" => $global_idusuarios, "tipo" => 'estado', "datos" => $_POST['estado']);
	novedades($novedad);
	die();
}


if ($_POST['tablon_leer_comentarios']) {
	if ($_POST['page']) {
		$limit = ($_POST['page'] - 1) * 10;
	} else {
		$limit = 0;
	}
	
	$sql = "SELECT idusuarios, nombre, apellidos, comentario, archivo, 
				 DATE_FORMAT(tablon.fecha, '%d/%m/%Y %H:%i') AS fecha2
				 FROM tablon, usuarios LEFT JOIN fotos ON idfotos = idfotos_princi
				 WHERE receptor='" . $_POST['idusuarios'] . "' AND emisor=idusuarios ORDER BY tablon.fecha DESC LIMIT {$limit}, 10";
	$q_comentarios = mysqli_query($link, $sql);
	
	
	if($_POST['idusuarios'] == $global_idusuarios){//TODO: marcar comentarios leidos
		//El visitante es el propietario del tablon
		$sql = "UPDATE tablon SET ledio='1' WHERE idtablon IN (
				     SELECT idtablon FROM tablon WHERE receptor='{$_POST['idusuarios']}' ORDER BY fecha DESC LIMIT {$limit}, 10) tmp";
		mysqli_query($link, $sql);
		error_mysql();
	}
	
	if (mysqli_num_rows($q_comentarios) > 0) {
		while ($r_comentarios = mysqli_fetch_assoc($q_comentarios)) {
			print "<div class='foto_comentario'> 
					 <img src='{$r_comentarios['archivo']}' class='imagen_perfil'> 
					 <div> 
						<div> 
							<div class='foto_come_titu'><a class='link' href='perfil.php?id={$r_comentarios['idusuarios']}'>{$r_comentarios['nombre']} {$r_comentarios['apellidos']}</a></div> 
							<div class='foto_come_fecha'>{$r_comentarios['fecha2']}</div> 
						</div> 
						<div class='foto_come_men'>{$r_comentarios['comentario']}</div> 
					</div> 
				</div>";
		}

		//Numeracion paginas
		$q_numeracion = mysqli_query($link, "
			SELECT count(*) AS total
			FROM tablon WHERE receptor='" . $_POST['idusuarios'] . "' ORDER BY fecha DESC");
		$r_numeracion = mysqli_fetch_assoc($q_numeracion);

		$siguiente = $_POST['page'] + 1;
		$anterior = $_POST['page'] - 1;
		$ultima = ceil($r_numeracion['total'] / 10);

		//BARRA NAVEGACION
		echo "<div id='barra_navegacion'>";
			if ($_POST['page'] > 1) {
				echo "<img class='flecha_back_top' src='css/flechas/flecha_left_top.jpg' onclick=\"tablon_leer_comentarios(idusuarios, 1);\">";
				echo "<img class='flecha_back' src='css/flechas/flecha_left.jpg' onclick=\"tablon_leer_comentarios(idusuarios, {$anterior});\">";
			}
			echo "<div class='texto'>".$_POST['page']." de ".$ultima."</div>";
			
		if ($_POST['page'] < $ultima) {
				echo "<img class='flecha_next' src='css/flechas/flecha_right.jpg' onclick=\"tablon_leer_comentarios(idusuarios, {$siguiente});\">";
				echo "<img class='flecha_next_top' src='css/flechas/flecha_right_top.jpg' onclick=\"tablon_leer_comentarios(idusuarios, " . $ultima . ");\">";
			}
		
	} else {
		echo "<div>Todavia nadie ha comentado el tablon</div>";
	}
	die();
}


if ($_POST['tablon_enviar_comentario'] != "") {
	// Comentario
	mysqli_query($link, "INSERT INTO tablon (emisor,receptor,comentario,fecha) VALUES ('" . $global_idusuarios . "','" . $_POST['receptor'] . "','" . $_POST['comentario'] . "', now())");

	// Notificacion
	$notificacion = array("propietario" => $_POST['receptor'], "tipo" => 'tablon');
	notificacion($notificacion);

	// Novedad
	$novedad = array("propietario" => $_POST['receptor'], "visitante" => $global_idusuarios, "tipo" => 'tablon', "datos" => $_POST['comentario']);
	novedades($novedad);
	die();
}

	//TODO: Tablon leido, notificaciones y novedades

########	AMISTAD

if ($_POST['peticion_amistad_enviar']) {
		//Comprobar no amistad
		$query = mysqli_query($link,"SELECT COUNT(*) AS cuenta FROM amigos WHERE user1='".$_POST['idusuario']."' AND user2='".$global_idusuarios."' OR user1='".$global_idusuarios."' AND user2='".$_POST['idusuario']."'");
		$row = mysqli_fetch_assoc($query);
		if($row['cuenta']>0)
			die("Ya es tu amigo!");
		
		//Comprobar no peticion
		$query = mysqli_query($link,"SELECT COUNT(*) FROM peticiones WHERE emisor='".$_POST['idusuario']."' AND receptor='".$global_idusuarios."' OR emisor='".$global_idusuarios."' AND receptor='".$_POST['idusuario']."'");
		$row = mysqli_fetch_assoc($query);
		if($row['cuenta']>0)
			die("Ya le enviastes una peticion de amistad!");
		
		//Insertar peticion
		mysqli_query($link,"INSERT INTO peticiones (emisor, receptor, fecha) VALUES ('".$global_idusuarios."', '".$_POST['idusuario']."', now())");
		
		// Notificacion
		$notificacion = array("propietario" => $_POST['idusuario'], "tipo" => 'peticion');
		notificacion($notificacion);
		die();
}

if ($_POST['peticion_amistad_aceptar']) {
	$query = mysqli_query($link, "SELECT COUNT(*) AS cuenta FROM amigos WHERE user1='" . $_POST['emisor'] . "' AND user2='" . $global_idusuarios . "' OR user1='" . $global_idusuarios . "' AND user2='" . $_POST['emisor'] . "'");
	$row = mysqli_fetch_assoc($query);
	if($row['cuenta']>0)
			die("Ya es tu amigo!");
	
	mysqli_query($link, "INSERT INTO amigos (user1,user2) VALUES ('" . $_POST['emisor'] . "','" . $global_idusuarios . "')");
	mysqli_query($link, "DELETE FROM peticiones WHERE emisor='" . $_POST['emisor'] . "' AND receptor='" . $global_idusuarios . "'");
	
	
	// Notificacion
	$notificacion = array("propietario" => $global_idusuarios, "tipo" => 'peticion');
	notificacion($notificacion);
	
	// Novedad
	$novedad = array("propietario" => $global_idusuarios, "visitante" => $_POST['emisor'], "tipo" => 'amistad');
	novedades($novedad);
	
	$novedad = array("visitante" => $global_idusuarios, "propietario" => $_POST['emisor'], "tipo" => 'amistad');
	novedades($novedad);
	die();
}



########	MENSAJERIA PRIVADA

if ($_POST['mp_enviar']) {
	// Insertar mensaje
	mysqli_query($link, "INSERT INTO mps (emisor,receptor,mp,fecha) VALUES ('{$global_idusuarios}','{$_POST['receptor']}','{$_POST['mensaje']}',now())");
	
	// Notificacion
	$notificacion = array("propietario" => $_POST['receptor'], "tipo" => 'mp');
	notificacion($notificacion);
	die();
}

if ($_POST['mp_leido'] != "") {
	// Marcar como leido
	mysqli_query($link, "UPDATE mps SET leido='1' WHERE idmps='" . $_POST['id'] . "'");
	
	// Notificacion
	$notificacion = array("propietario" => $global_idusuarios, "tipo" => 'mp');
	notificacion($notificacion);
	die();
}



########	ALBUM

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
	mysqli_query($link, "DELETE FROM albums WHERE idalbums='" . $_POST['album_borrar'] . "' AND usuarios_idusuarios='" . $global_idusuarios . "'");
	die();
}

########	FOTOS
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

	error_mysql();
	die();
}

if ($_POST['foto_principal']) {
	mysqli_query($link, "UPDATE usuarios SET idfotos_princi='" . $_POST['foto_principal'] . "' WHERE idusuarios='" . $global_idusuarios . "'");
	
	// Novedad
	$novedad = array("propietario" => $global_idusuarios, "tipo" => 'foto_principal', "datos" => $_POST['foto_principal']);
	novedades($novedad);
	die();
}

if ($_GET['foto_borrar']) {
	$result = mysqli_query($link, "SELECT * from fotos WHERE uploader='" . $global_idusuarios . "' AND idfotos='" . $_GET['foto_borrar'] . "'");
	if (mysqli_num_rows($result) == 1) {
		$result = mysqli_fetch_assoc($result);
		unlink($result['archivo']);

		mysqli_query($link, "DELETE FROM fotos WHERE uploader='" . $global_idusuarios . "' AND idfotos='" . $_GET['foto_borrar'] . "'");
	}
	die();
}

if ($_POST['foto_comentario']) {
	mysqli_query($link, "INSERT INTO fotos_comentarios (fotos_idfotos,emisor,comentario,fecha) VALUES ('" . $_POST['idfotos'] . "','" . $global_idusuarios . "','" . $_POST['foto_comentario'] . "',now())");
	//TODO: Novedad/Notificar comentario a dueño & etiquetados
	die();
}

if ($_POST['foto_leer_comentarios']) {
	//TODO: Notificaciones, marcar comentarios como leidos
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

		$siguiente = $_POST['page'] + 1;
		$anterior = $_POST['page'] - 1;
		$ultima = ceil($r_numeracion['total'] / 10);

		//BARRA NAVEGACION
		echo "<div id='barra_navegacion'>";
			if ($_POST['page'] > 1) {
				echo "<img class='flecha_back_top' src='css/flechas/flecha_left_top.jpg' onclick=\"foto_leer_comentarios(idfoto,1);\">";
				echo "<img class='flecha_back' src='css/flechas/flecha_left.jpg' onclick=\"foto_leer_comentarios(idfoto,{$anterior});\">";
			}
			echo "<div class='texto'>".$_POST['page']." de ".$ultima."</div>";
			
		if ($_POST['page'] < $ultima) {
				echo "<img class='flecha_next' src='css/flechas/flecha_right.jpg' onclick=\"foto_leer_comentarios(idfoto,{$siguiente});\">";
				echo "<img class='flecha_next_top' src='css/flechas/flecha_right_top.jpg' onclick=\"foto_leer_comentarios(idfoto," . $ultima . ");\">";
			}
		
	} else {
		echo "<div>Todavia nadie ha comentado esta foto</div>";
	}
	die();
}


########	CHAT
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



########	AJUSTES -> DATOS
if ($_POST['cambio_email']) {
	//print_r($_POST);
	if($_POST['email'] AND $_POST['email_new'] AND $_POST['pass']){
		$patron = '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
		if (preg_match($patron, $_POST['email_new'])) {
			$password = sha1($_POST['pass']);
			$sql = "UPDATE usuarios SET email='{$_POST['email_new']}' WHERE email='{$_POST['email']}' AND password='{$password}'";
			$query = mysqli_query($link, $sql);
			if(mysqli_affected_rows($link)!=1){
				echo "Los datos introducidos no son correctos";
			}
		}else{
			echo "El email nuevo no tiene un formato correcto";
		}
	}else{
		echo "Rellena todos los campos";
	}
	die();
}

if ($_POST['cambio_pass']) {
	//print_r($_POST);
	if($_POST['pass'] AND $_POST['pass_new'] AND $_POST['pass_new2']){
		if($_POST['pass_new'] == $_POST['pass_new2']){
			$password_old = sha1($_POST['pass']);
			$password_new = sha1($_POST['pass_new']);
			
			$sql = "UPDATE usuarios SET password='{$password_new}' WHERE password='{$password_old}'";
			$query = mysqli_query($link, $sql);
			
			if(mysqli_affected_rows($link)!=1){
				echo "La contraseña actual no es correcta";
			}
		}else{
			echo "Las contraseñas nuevas no coinciden";
		}
	}else{
		echo "Rellena todos los campos";
	}
	die();
}

if ($_POST['cambio_datos']) {
	//print_r($_POST);
	foreach($_POST as $nombre_campo => $valor)
	{
	  if(!$valor){
		echo "Rellena todos los campos, incluido ".$nombre_campo;
		die();
		break;
	  }
	}
	
	$sql = "UPDATE usuarios
			SET nombre='{$_POST['nombre']}', 
			apellidos='{$_POST['apellidos']}', 
			sexo='{$_POST['sexo']}', 
			provincia='{$_POST['provincia']}', 
			fnac='{$_POST['fecha_hidden']}'
			 WHERE idusuarios='{$global_idusuarios}'";

	$query = mysqli_query($link, $sql);
	
	if($query!=1){
		echo "Se ha producido un error";
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
echo "<pre>";
 if($_POST){
 echo "POST:<br>";
 print_r($_POST);
 echo "<br>";
 }

 if($_GET){
 echo "GET:<br>";
 print_r($_GET);
 }
 echo "</pre>";
echo "ERROR";
?>