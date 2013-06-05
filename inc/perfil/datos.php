<div class="marco_small">
	<?php
	echo "<img alt='foto principal' style='max-height:200px;max-width:180px;' src='" . $r_user['archivo'] . "' /><br>";
	echo $r_user['nombre'] . " " . $r_user['apellidos']."<br>";
	echo $r_user['edad']." años<br>";
	if($perfil == "ajeno"){
		echo "<a href='mp.php?modo=enviar&receptor=".$r_user['idusuarios']."'>Enviar un mensaje privado</a><br>";
		echo "<a href='albums.php?iduser=".$r_user['idusuarios']."'>Ver albums de fotos</a><br>";
		echo "Registrado el ".$r_user['fecha_reg']."<br>";
		
		if($r_user['segundos_off'] < 120){
			echo "Estado: Conectado<br>";
		}else{
			echo "Ultima visita: ".fecha($r_user['online'])."<br>";
		}
	}
	?>
</div>