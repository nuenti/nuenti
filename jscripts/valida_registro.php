	<?php
	header ('Content-Type: text/javascript; charset=utf-8');
	require_once("../inc/validador.class.php");
	$Validador = new Validador();
	$Validador->SetInput(array('name' => 'Nombre', 'min' => '4'));
	$Validador->SetInput(array('name' => 'Apellidos', 'obligatorio' => 'yes'));
	$Validador->SetInput(array('name' => 'contrasenia', 'alias' => 'Contraseña', 'min' => '4'));
	$Validador->SetInput(array('name' => 'contrasenia2', 'alias' => 'Repita la Contraseña', 'semejante' => 'contrasenia,Contraseña'));
	$Validador->SetInput(array('name' => 'Email', 'formato' => '^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$'));
	$Validador->SetInput(array('name' => 'nacimiento', 'alias' => 'Fecha de nacimiento','formato' => '^\d{1,2}\/\d{1,2}\/\d{4,4}$'));
	$Validador->SetInput(array('name' => 'Sexo', 'radio' => 'yes'));
	$Validador->SetInput(array('name' => 'tos', 'alias' => 'Terminos de Uso', 'checkbox' => 'yes'));
	$Validador->SetInput(array('name' => 'Provincia', 'select' => 0));
	$Validador->GeneraValidadorJS();
?>