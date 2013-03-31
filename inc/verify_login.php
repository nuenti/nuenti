<?php
	session_start();
	require('config.php');
	if(isset($_SESSION['idsesion'])) {
		$login=mysqli_query($link,"SELECT *,
			FLOOR(DATEDIFF(CURDATE(),fnac)/365) AS edad
			FROM usuarios WHERE idsesion='".$_SESSION['idsesion']."'");
		//echo mysqli_num_rows($login);
		if(mysqli_num_rows($login)!=1){
			header("Location: logout.php?auth_error");
		}
		$usuario=mysqli_fetch_assoc($login);
		$global_idusuarios=$usuario['idusuarios'];
		$global_nombre=$usuario['nombre'];
		$global_apellidos=$usuario['apellidos'];
		$global_nombrefull=$usuario['nombre']." ".$usuario['apellidos'];
		$global_idsesion=$usuario['idsesion'];
	} else {
		header("Location: logout.php?no_sesion");
	}
?>