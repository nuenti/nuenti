	<?php
	header ('Content-Type: text/javascript; charset=utf-8');
	require_once("../inc/validador.class.php");
	$Validador = new Validador();
	$Validador->SetInput(array('name' => 'email', 'formato' => '^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$'));
	$Validador->SetInput(array('name' => 'password', 'alias' => 'Clave', 'min' => '4'));
	$Validador->GeneraValidadorJS();
?>