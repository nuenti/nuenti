<?php
function NombreApellido($input, $maximo = 18) {//si no se pasa segudo parametro sera X por defecto
	$minimo = 10;
	//Posicion minima del espacio, evita nombres cortos
	$position = $maximo;
	//Si no se detecta espacio se coge el maximo

	preg_match_all("/ /", $input, $coincidencias, PREG_OFFSET_CAPTURE);

	for ($i = 0; $i < 5; $i++) {
		//echo $coincidencias[0][$i][1]." - "; //Muestra posicion del espacion
		if ($coincidencias[0][$i][1] > $minimo AND $coincidencias[0][$i][1] < $maximo) {//Si el espacio esta entre el minimo y el maximo se coje
			$position = $coincidencias[0][$i][1];
		}

		if ($coincidencias[0][$i][1] > $maximo) {//Si el espacio supera el limite paramos
			break;
		}
	}

	$nombre_apellido = substr($input, 0, $position);
	return $nombre_apellido;
}

//LIMPIA CADENAS
function limpia_texto($string) {
	$string = trim($string);
	$string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
	$string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
	$string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
	$string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
	$string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
	$string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string);
	$string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "<", ";", ",", ":", ".", " "), '-', $string);
	return $string;
}

//ERROR MYSQL
function error_mysql($p1 = "exit") {
	global $link;
	if (mysqli_errno($link)) {
		echo "No error: " . mysqli_errno($link) . " -> " . mysqli_error($link) . "<br>";
		if ($p1 == "exit") {
			die("Script detenido por fallo PHP");
		}
	}
}

function fecha_a_segundos($input) {
	$segundos = $input -> format('%y') * 365 * 24 * 60 * 60;
	$segundos += $input -> format('%m') * 31 * 24 * 60 * 60;
	$segundos += $input -> format('%d') * 24 * 60 * 60;
	$segundos += $input -> format('%h') * 60 * 60;
	$segundos += $input -> format('%i') * 60;
	$segundos += $input -> format('%s');
	
	//60 minuto
	//3600 hora
	//86.400 dia
	//604.800 semana
	//2.592.000 mes
	//31.104.000 año
	return $segundos;
}

function fecha_intervalo($oldDate, $newDate = "default") {
	if($newDate == "default") $newDate = new DateTime("now");
	$oldDate = new DateTime($oldDate);
	$intervalo = date_diff($oldDate, $newDate);
	
	return $intervalo;
}

function fecha($fecha) {
	
	$intervalo = fecha_intervalo($fecha);
	$segundos = fecha_a_segundos($intervalo);


	$buffer = "";
	if ($segundos < 60) {
		$buffer .= "hace unos segundos";
	} elseif ($segundos < 3600) {
		$buffer .= "hace {$intervalo->format('%i')} minuto";
		if ($intervalo -> format('%i') != 1)
			$buffer .= "s";
	} elseif ($segundos < 86400) {
		$buffer .= "hace {$intervalo->format('%h')} hora";
		if ($intervalo -> format('%h') != 1)
			$buffer .= "s";
	} elseif ($segundos < 604800) {
		$buffer .= "hace {$intervalo->format('%d')} dia";
		if ($intervalo -> format('%d') != 1)
			$buffer .= "s";
	} elseif ($segundos < 2592000) {
		$semanas = floor($intervalo -> format('%d') / 7);
		$buffer .= "hace {$semanas} semana";
		if ($semanas != 1)
			$buffer .= "s";
	} elseif ($segundos < 31104000) {
		$buffer .= "hace " . ltrim($intervalo -> format('%M'), '0') . " mes";
		if (ltrim($intervalo -> format('%M'), '0') != 1)
			$buffer .= "es";
	} else {
		$buffer .= "hace {$intervalo->format('%d')} dias ELSE";
	}

	//Debug
	//echo "Intervalo: " . $intervalo -> format('%Y-%m-%d %H:%i:%s') . "<br>";
	//echo "Segundos: " . $segundos . "<br>";
	return $buffer;
}

//CABECERA
function head($title = Sitio) {
	print "
		<!DOCTYPE html>
		<html lang='es'>
			<head>
				<!-- METAS -->
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<meta name='Keywords' content='Social,red social,gratuito,asir'>
				<meta name='Description' content='Proyecto de Red Social de 2 ASIR'>
				 <meta name='author' content='Javier Gonzalez Rastrojo'>
				<!-- FAVICON -->
				<link rel='shortcut icon' href='css/favicon.ico' />
				<link rel='icon' href='css/favicon.ico' type='image/x-ico' />
				<link rel='shortcut icon' href='css/favicon.ico' type='image/x-icon'>
				<!-- CSS -->
				<link rel='stylesheet' href='css/style.css' type='text/css' />
				<link rel='stylesheet' href='jscripts/jquery-ui/css/redmond/jquery-ui-1.9.0.custom.css' type='text/css' />
				<!-- JS -->
				<script type='text/javascript' src='jscripts/jquery-1.8.2.min.js'></script>
				<script type='text/javascript' src='jscripts/jquery-ui/jquery-ui-1.9.0.js'></script>
				<script type='text/javascript' src='jscripts/general.js'></script>
				<script type='text/javascript' src='jscripts/chat.js'></script>
				<script  type='text/javascript'>";

	if ($_SESSION['chat_estado'] == "1") {
		echo "chat_estado=1;";
	} else {
		echo "chat_estado=0;";
	}
	print "</script>
				<title>{$title}</title>";
}

// Pasamos el ID de la provincia y nos devuelve el nombre
function IdProvincia($id){
	switch ($id) {
		case '0': return "Sin provincia"; break;
		case '2': return "Álava"; break;
		case '3': return "Albacete"; break;
		case '4': return "Alicante"; break;
		case '5': return "Almería"; break;
		case '6': return "Asturias"; break;
		case '7': return "Ávila"; break;
		case '8': return "Badajoz"; break;
		case '9': return "Barcelona"; break;
		case '10': return "Burgos"; break;
		case '11': return "Cáceres"; break;
		case '12': return "Cádiz"; break;
		case '13': return "Cantabria"; break;
		case '14': return "Castellón"; break;
		case '15': return "Ceuta"; break;
		case '16': return "Ciudad Real"; break;
		case '17': return "Córdoba"; break;
		case '18': return "Cuenca"; break;
		case '19': return "Girona"; break;
		case '20': return "Las Palmas"; break;
		case '21': return "Granada"; break;
		case '22': return "Guadalajara"; break;
		case '23': return "Guipúzcoa"; break;
		case '24': return "Huelva"; break;
		case '25': return "Huesca"; break;
		case '26': return "Islas Baleares"; break;
		case '27': return "Jaén"; break;
		case '28': return "A Coruña"; break;
		case '29': return "La Rioja"; break;
		case '30': return "León"; break;
		case '31': return "Lleida"; break;
		case '32': return "Lugo"; break;
		case '33': return "Madrid"; break;
		case '34': return "Málaga"; break;
		case '35': return "Melilla"; break;
		case '36': return "Murcia"; break;
		case '37': return "Navarra"; break;
		case '38': return "Ourense"; break;
		case '39': return "Palencia"; break;
		case '40': return "Pontevedra"; break;
		case '41': return "Salamanca"; break;
		case '42': return "Segovia"; break;
		case '43': return "Sevilla"; break;
		case '44': return "Soria"; break;
		case '45': return "Tarragona"; break;
		case '46': return "Santa C. de Tenerife"; break;
		case '47': return "Teruel"; break;
		case '48': return "Toledo"; break;
		case '49': return "Valencia"; break;
		case '50': return "Valladolid"; break;
		case '51': return "Vizcaya"; break;
		case '52': return "Zamora"; break;
		case '53': return "Zaragoza"; break;
	}
}

function notificacion($notificacion){
	global $link;
	
	mysqli_query($link, "DELETE FROM notificaciones WHERE usuarios_idusuarios='".$notificacion['propietario']."' AND tipo='{$notificacion['tipo']}'");
	
	if($notificacion['tipo'] == "tablon")
	{
		$query = mysqli_query($link,"SELECT COUNT(*) AS cuenta FROM tablon WHERE receptor='".$notificacion['propietario']."' AND leido='0'");
	}
	elseif($notificacion['tipo'] == "mp")
	{
		$query = mysqli_query($link,"SELECT COUNT(*) AS cuenta FROM mps WHERE receptor='".$notificacion['propietario']."' AND leido='0'");
	}
	elseif($notificacion['tipo'] == "peticion")
	{
		$query = mysqli_query($link,"SELECT COUNT(*) AS cuenta FROM peticiones WHERE receptor='".$notificacion['propietario']."' AND ignorada<>'1'");
	}
	
	$row = mysqli_fetch_assoc($query);
	$num = $row['cuenta'];
	mysqli_query($link,"INSERT INTO notificaciones (tipo, usuarios_idusuarios, datos) VALUES ('{$notificacion['tipo']}', '{$notificacion['propietario']}', '{$num}')");
	
	error_mysql();
}

function novedades($novedad){
	global $link;
	
	if($novedad['visitante']){
		$novedad['visitante'] = "'".$novedad['visitante']."'";
	}else{
		$novedad['visitante'] = "NULL";
	}
	
	mysqli_query($link, "INSERT INTO novedades (fecha, tipo, propietario, visitante, datos) VALUES (now(), '{$novedad['tipo']}', '{$novedad['propietario']}', {$novedad['visitante']}, '{$novedad['datos']}')");
	error_mysql("exit");
}

function email_send($destinatario_name, $destinatario_email, $titulo, $mensaje){
	 $TextMessage = strip_tags(nl2br($mensaje), "<br>");
	 $HTMLMessage = $mensaje;
	 
	/*$To = strip_tags($to);
	 $TextMessage = strip_tags(nl2br($comment), "<br>");
	 $HTMLMessage = nl2br($comment);
	 $FromName = strip_tags($name);
	 $FromEmail = strip_tags($email);
	 $Subject = strip_tags($subject);*/
	
	$boundary = rand(0, 9) . "-" . rand(10000000000, 9999999999) . "-" . rand(10000000000, 9999999999) . "=:" . rand(10000, 99999);
	
	//CABECERAS
	$Headers = "MIME-Version: 1.0\r\n";
	$Headers .= "To: {$destinatario_name} <{$destinatario_email}>\r\n";
	$Headers .= "From: ".Sitio." <".Email_Address.">\r\n";
	$Headers .= "Reply-To: ".Email_Address."\r\n";
	$Headers .= "Content-Type: multipart/alternative; boundary=$boundary\r\n";
	

//CUERPO DEL MENSAJE (DEBE IR PEGADO A LA IZQ)
$Body = "MIME-Version: 1.0
Content-Type: multipart/alternative; boundary=$boundary

--$boundary
Content-Type: text/plain; charset=ISO-8859-1
	
$TextMessage

--$boundary
Content-Type: text/html; charset=\"UTF-8\"
 Content-Transfer-Encoding: 8bit
	
$HTMLMessage

--$boundary--";

	$ok = mail($destinatario_email, $titulo, $Body, $Headers);
	return $ok ? TRUE : FALSE;
}
?>