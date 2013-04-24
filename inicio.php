<?php
$tiempo_inicio = microtime(true);
require ("inc/verify_login.php");
head("Inicio - Social");
require ("inc/estructura.inc.php");
?>
<div class="barra_izq">
	<div class="marco_small">
		<?php
			########## NEWS ##############
			
		?>
	</div>
	
	<!-- ########## CHAT #############-->
	<div class="marco_small">
		<script language="JavaScript">
			$(window).ready(function() {
				chat_mode_home = true;
				$("#chat_boton").remove();
			});
		</script>
		<div id="chat">
			<h2>Chat</h2>
			<div id="chat_opciones"  onclick="chat_opciones()">
				<div id="chat_opciones_lista" class="marco_small">
					<div id="chat_estado">
						<?php
						if ($_SESSION['chat_estado'] != "on") {
							echo "<p style='cursor:pointer;' onclick=\"chat_turn('on')\">Activar Chat</p>";
						} else {
							echo "<p style='cursor:pointer;' onclick=\"chat_turn('off')\">Desactivar Chat</p>";
						}
						?>
					</div>
				</div>
			</div>

			<!--<div id="chat_boton" onclick="chat_toggle()"></div>-->
			<div id="chat_contactos" style="position: relative;top: -25px;"></div>
			<div id='chat_conv_tmp' style='display:none;'></div>
		</div>
	</div>
</div>

<div class="barra_centro_der">
	<div class="marco" style="width: 717px;">
		<h2>Lista de amigos</h2>
		<?php
		$query = mysqli_query($link, "
			SELECT *
			FROM amigos, usuarios
			WHERE user1='" . $global_idusuarios . "' AND user2=idusuarios OR user2='" . $global_idusuarios . "' AND user1=idusuarios
			");
		if (mysqli_num_rows($query) > 0) {
			//echo "Tienes ".mysqli_num_rows($query)." peticion(es) de amistad:<br>";
			while ($row = mysqli_fetch_assoc($query)) {
				//print_r($row);
				echo $row['nombre'] . " " . $row['apellidos'] . " <a href='gente.php?id=" . $row['idusuarios'] . "'>Ver perfil</a><br>";

			}
		}
		?>
	</div>
	
	<div class="marco" style="width: 717px;">
		<h2>Peticiones de amistad</h2>
		<?php
		$query = mysqli_query($link, "SELECT * FROM peticiones, usuarios WHERE receptor = '" . $global_idusuarios . "' AND idusuarios = emisor");
		if (mysqli_num_rows($query) > 0) {
			echo "Tienes " . mysqli_num_rows($query) . " peticion(es) de amistad:<br>";
			while ($row = mysqli_fetch_assoc($query)) {
				//print_r($row);
				echo $row['nombre'] . " " . $row['apellidos'] . " <a href='post.php?aceptaramistad=1&emisor=" . $row['idusuarios'] . "'>Aceptar peticion</a><br>";
			}
		}
		?>

		<?php
		require ("inc/novedades.inc.php");
		?>
	</div>
</div>
<?php
require ("inc/chat.php");
$tiempo_fin = microtime(true);
echo "<br>Tiempo de ejecución redondeado: " . round($tiempo_fin - $tiempo_inicio, 4) . "<br>";
?>
Sin novedades: 0.02 max
<br>
Con consulta principal: 0.02 max
