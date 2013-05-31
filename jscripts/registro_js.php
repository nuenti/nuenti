<?php
	header ('Content-Type: text/javascript; charset=utf-8');
?>

// JS REGISTRO
$(function() {
	var date = new Date();
	var year = date.getFullYear();
	var top_year = year - 14;
	$("#nacimiento").datepicker({
		changeMonth : true,
		changeYear : true,
		yearRange : "1940:"+top_year,
		defaultDate : "-20 y",
		dateFormat: "d 'de' MM 'de' yy",
		altField: "#nacimiento_hidden",
		altFormat: "yy-mm-dd",
	});
}); 

<?php
	// VALIDADOR
	require_once("../inc/validador.class.php");
	$Validador = new Validador();
	$Validador->SetInput(array('name' => 'Nombre', 'min' => '4'));
	$Validador->SetInput(array('name' => 'Apellidos', 'obligatorio' => 'yes'));
	$Validador->SetInput(array('name' => 'contrasenia', 'alias' => 'Contraseña', 'min' => '4'));
	$Validador->SetInput(array('name' => 'Email', 'formato' => '^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$'));
	//$Validador->SetInput(array('name' => 'nacimiento', 'alias' => 'Fecha de nacimiento','formato' => '^\d{1,2}\/\d{1,2}\/\d{4,4}$'));
	$Validador->SetInput(array('name' => 'nacimiento', 'alias' => 'Fecha de nacimiento','formato' => '^\d{1,2} de \w{4,10} de \d{4,4}$'));
	$Validador->SetInput(array('name' => 'Provincia', 'tipo' => 'select'));
	$Validador->SetInput(array('name' => 'Sexo', 'tipo' => 'radio'));
	$Validador->SetInput(array('name' => 'tos', 'alias' => 'Terminos de Uso', 'tipo' => 'checkbox'));
	$Validador->GeneraValidadorJS();
?>