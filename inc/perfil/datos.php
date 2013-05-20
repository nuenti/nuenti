<div class="marco_small">
	<?php
	echo "<img alt='foto principal' style='max-height:200px;max-width:180px;' src='" . $r_user['archivo'] . "' /><br>";
	echo $r_user['nombre'] . " " . $r_user['apellidos'];
	echo "<br>Edad: " . $r_user['edad']."<br>";
	if($perfil == "ajeno"){
		echo "<a href='mp_redactar.php?receptor=".$r_user['idusuarios']."'>Enviar un mensaje privado</a><br>";
		echo "<a href='albums.php?iduser=".$r_user['idusuarios']."'>Ver albums de fotos</a><br>";
		echo "Registrado el ".$r_user['fecha_reg']."<br>";
	}
	?>
</div>