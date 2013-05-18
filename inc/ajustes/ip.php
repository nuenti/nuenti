<h3>Control de acceso a la cuenta </h3>
<p>
	Listado de los accesos a la cuenta con la fecha y dirección IP
</p>
<table>
	<tr>
		<th>Fecha de acceso</th>
		<th>Direccion IP</th>
	</tr>
	<?php
	$sql = "SELECT *, DATE_FORMAT(fecha, '%W %e de %M %Y a las %H:%i') AS fechaf
				FROM accesos
				WHERE usuarios_idusuarios = '{$global_idusuarios}' ORDER BY fecha DESC";
	$q_accesos = mysqli_query($link, $sql);
	while ($r_accesos = mysqli_fetch_assoc($q_accesos)) {
		print "
			<tr>
				<td>" . utf8_encode(ucfirst($r_accesos['fechaf'])) . "</td>
				<td style='text-align:center;'>{$r_accesos['ip']}</td>
			</tr>
		";
	}
?>
</table>