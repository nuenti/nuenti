<?php
require ("inc/verify_login.php");

if ($_GET['id'] == $global_idusuarios)	$_GET['id']=NULL; //Si el id es el mio, se ve la pagina en modo normal

if ($_GET['id']) {
	//Comprueba amistad
	$query = mysqli_query($link, "SELECT COUNT(*) AS cuenta FROM amigos WHERE user1='" . $_GET['id'] . "' AND user2='" . $global_idusuarios . "' OR user2='" . $_GET['id'] . "' AND user1='" . $global_idusuarios . "'");
	$row = mysqli_fetch_assoc($query);
	if ($row['cuenta'] != 1) {
		header("Location: inicio.php?nosoisamigos");
		die();
	}
	$perfil = "ajeno";
} else {
	$_GET['id'] = $global_idusuarios;
	$perfil = "propio";
}
$sql = "SELECT *,
			FLOOR(DATEDIFF(CURDATE(),fnac)/365) AS edad,
			DATE_FORMAT(fecha_reg, '%d/%m/%Y') AS fecha_reg,
			TIME_TO_SEC(TIMEDIFF(now(),online)) AS segundos_off
		FROM `usuarios` LEFT JOIN fotos ON idfotos = idfotos_princi WHERE idusuarios='" . $_GET['id'] . "'";
$q_user = mysqli_query($link, $sql);
$r_user = mysqli_fetch_assoc($q_user);

if ($perfil == "ajeno") {
	head(NombreApellido($r_user['nombre'] . " " . $r_user['apellidos']) . " - Social");
} else {
	head("Perfil - Social");
}
require ("inc/estructura.inc.php");
?>

<div class="barra_izq">
	<?php
	require 'inc/perfil/datos.php';
	?>
</div>

<div class="barra_centro_der" >
	<?php
	require 'inc/perfil/tablon.php';
	?>
	<?php
	require 'inc/perfil/comentarios.php';
	?>
</div>

<?php
require ("inc/chat.php");
//TODO: Debug time
$tiempo_fin = microtime(true);
echo "<br>Tiempo de ejecución redondeado: " . round($tiempo_fin - $tiempo_inicio, 4) . "<br>";
$q_querys = mysqli_query($link, "SHOW SESSION STATUS LIKE 'Questions'");
$r_querys = mysqli_fetch_array($q_querys);
define("STOP_QUERIES", $r_querys['Value']);
echo "No of queries: " . (STOP_QUERIES - START_QUERIES - 1);
?>