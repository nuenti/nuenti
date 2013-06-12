<div id="chat_anclaje">
<div id="chat_boton" onclick="chat_toggle()"></div>
	<div id="chat_padre_contactos">
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
</div>


