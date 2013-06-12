<?php
$tiempo_inicio = microtime(true);
require ("inc/verify_login.php");
head("Inicio - Social");
require ("inc/estructura.inc.php");
?>
<div class="barra_izq">
	<?php
		########## NEWS ##############
		$sql = "SELECT * FROM notificaciones WHERE usuarios_idusuarios='{$global_idusuarios}' AND datos<>0";
		$q_notifi = mysqli_query($link, $sql);
		if(mysqli_num_rows($q_notifi)>0){
			print "<div class='marco_small'>";
			while($r_notifi = mysqli_fetch_assoc($q_notifi)){
				if($r_notifi['tipo'] == "peticion"){
					print "<a href='ajustes.php?seccion=peticiones'>Tienes {$r_notifi['datos']} peticiones de amistad</a><br>";
				}elseif($r_notifi['tipo'] == "mp"){
					print "<a href='mp.php?modo=recibidos'>Tienes {$r_notifi['datos']} mensaje privado</a><br>";
				}elseif($r_notifi['tipo'] == "tablon"){
					print "<a href='perfil.php'>Tienes {$r_notifi['datos']} comentarios en tu tablon</a><br>";
				}
			}
			print "</div>";
		}
	?>
	
	<!-- ########## CHAT #############-->
	<div class="marco_small">
		<script language="JavaScript">
			$(window).ready(function() {
				chat_mode_home = true;
				$("#chat_boton").remove();
			});
		</script>
		<div id="chat">
			<?php
			if ($_SESSION['chat_estado'] == "1"){
				echo "<div id='encabezado'>";
			}else{
				echo "<div id='encabezado' style='display:none;'>";
			}
			?>
				<h2>Chat</h2>
				<div id='desactivar' onclick="chat_turn('0')" title='Desactivar Chat'></div>
			</div>
			
			<?php
				if ($_SESSION['chat_estado'] != "1"){
					echo "<div class='centrar'><button id='activar' type='button' class='azul' onclick=\"chat_turn('1')\"><span><b>Activar Chat</b></span></button></div>";
				}else{
					echo "<div class='centrar'><button id='activar' type='button' class='azul' onclick=\"chat_turn('1')\" style='display:none;'><span><b>Activar Chat</b></span></button></div>";
				}
			?>

			<div id="chat_contactos" style="position: relative;top: -25px;"></div>
			<div id='chat_conv_tmp' style='display:none;'></div>
		</div>
	</div>
</div>

<div class="barra_centro_der">
	<div class="marco" style="width: 717px;">
		<?php
			require ("inc/novedades.inc.php");
		?>
	</div>
	
</div>
<?php
require ("inc/chat.php");
$tiempo_fin = microtime(true);
echo "<br>Tiempo de ejecución redondeado: " . round($tiempo_fin - $tiempo_inicio, 4) . "<br>";
$q_querys = mysqli_query($link, "SHOW SESSION STATUS LIKE 'Questions'");
$r_querys = mysqli_fetch_array($q_querys);
define("STOP_QUERIES", $r_querys['Value']);
echo "No of queries: " . (STOP_QUERIES - START_QUERIES - 1);
?>
