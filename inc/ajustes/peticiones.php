<script>
	function peticion_aceptar(idusuario){		
		ajax_post({
			data : "peticion_amistad_aceptar=1&emisor="+idusuario,
			reload : true
		});

	}
</script>
<?php
$sql = "SELECT *, archivo, DATE_FORMAT(peticiones.fecha, '%d/%m/%Y %H:%i') AS fechaf
		FROM peticiones, usuarios
		RIGHT JOIN fotos
		ON idfotos=idfotos_princi
		WHERE receptor = '" . $global_idusuarios . "' AND idusuarios = emisor";

$q_peticiones = mysqli_query($link, $sql);
if (mysqli_num_rows($q_peticiones) > 0) {
	echo "Tienes " . mysqli_num_rows($q_peticiones) . " peticion(es) de amistad:<br>";
	echo "<div id='peticiones_amistad'>";
	while ($r_peticiones = mysqli_fetch_assoc($q_peticiones)) {
		//print_r($row);
		print "
			<div class='peticion_amistad'>
					<img src='{$r_peticiones['archivo']}'>
					<div class='nombre'>{$r_peticiones['nombre']} {$r_peticiones['apellidos']} te ha enviado una solicitud de amistad</div>
					<div class='fecha'>{$r_peticiones['fechaf']}</div><br>
					<div class='acciones'>
						<div class='texto' onclick='peticion_aceptar({$r_peticiones['idusuarios']})'>Aceptar</div>
						<div class='texto'>Ignorar</div>
					</div>
			</div>
		";
		//echo $row['nombre'] . " " . $row['apellidos'] . " <a href='post.php?aceptaramistad=1&emisor=" . $row['idusuarios'] . "'>Aceptar peticion</a><br>";
	}
	echo "</div>";
}
?>