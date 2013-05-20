<div id="chat_anclaje">
<div id="chat_boton" onclick="chat_toggle()"></div>
	<div id="chat_padre_contactos">
		<h2>Chat</h2>
			<div id="chat_opciones"  onclick="chat_opciones()">
				<div id="chat_opciones_lista" class="marco_small">
					<div id="chat_estado">
						<?php
						if ($_SESSION['chat_estado'] != "1"){
							echo "<p style='cursor:pointer;' onclick=\"chat_turn('on')\">Activar Chat</p>";
						}else{
							echo "<p style='cursor:pointer;' onclick=\"chat_turn('off')\">Desactivar Chat</p>";
						}
						?>
					</div>
				</div>
			</div>
		<div id="chat_contactos" style="margin-top: -25px;">
			<?php
			if($_SESSION['chat_estado']!="1")
				echo "<p style='cursor:pointer;' onclick=\"chat_turn('on')\">Activar Chat</p>";
			?>
		</div>
	</div>
	<div id='chat_conv_tmp' style='display:none;'></div>
</div>
</div>


