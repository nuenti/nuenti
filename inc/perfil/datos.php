<div class="marco_small">
	<?php
	echo $r_user['nombre'] . " " . $r_user['apellidos']."<br>";
	echo "<div style='text-align:center;'><img alt='foto principal' style='max-height:200px;max-width:180px;' src='" . $r_user['archivo'] . "' /></div>";

	if($perfil == "ajeno"){
		echo "<div style='text-align:center;'><button type='button' class='azul' onclick=\"location.href='mp.php?modo=enviar&receptor=".$r_user['idusuarios']."'\"><span><b>Enviar mensaje</b></span></button></div>";
	}
	
	echo $r_user['edad']." años<br>";
	echo "Registrado el ".$r_user['fecha_reg']."<br>";
	
	if($perfil == "ajeno"){
		if($r_user['segundos_off'] < 120){
			echo "<div class='conectado'></div>Conectado<br>";
		}else{
			echo "Ultima visita: ".fecha($r_user['online'])."<br>";
		}
	}	
	?>
</div>