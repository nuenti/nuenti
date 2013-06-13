<?php
require ('inc/config.php');

########	TERMINOS DE USO
if($_GET['tos']){
	require 'inc/otros/tos.php';
	die();
}

########	RECUPERAR CONTRASEÑA
if($_GET['restore_pass'] OR $_POST['restore_pass']){
	require 'inc/otros/restore_pass.php';
	die();
}

########	ACTIVAR CUENTA
if($_GET['activar_cuenta']){
	require 'inc/otros/activacion.php';
	die();
}
?>