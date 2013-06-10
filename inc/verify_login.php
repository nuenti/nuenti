<?php
	session_start();
	require('inc/config.php');
	
	if(isset($_SESSION['idsesion'])) {
		$login=mysqli_query($link,"SELECT *	FROM usuarios WHERE idsesion='".$_SESSION['idsesion']."' AND activacion = '1'");
		
		if(mysqli_num_rows($login)!=1){
			header("Location: logout.php?auth_error");
		}
		
		$usuario = mysqli_fetch_assoc($login);
		$global_idusuarios = $usuario['idusuarios'];
		$global_nombre = $usuario['nombre'];
		$global_apellidos = $usuario['apellidos'];
		$global_nombrefull = $usuario['nombre']." ".$usuario['apellidos'];
		$global_idsesion = $usuario['idsesion'];
		$_SESSION['chat_estado'] = $usuario['chat_estado'];
	} else {
		header("Location: logout.php?no_sesion");
	}
?>