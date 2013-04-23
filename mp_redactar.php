<?php
	require("inc/verify_login.php");
	head("Mensajeria Privada - Social");
	require("inc/estructura.inc.php");

	require_once("inc/validador.class.php");
	$Validador = new Validador();
	$Validador->SetInput(array('name' => 'mensaje_privado', 'alias' => 'Mensaje', 'min' => '2'));
	$Validador->GeneraValidadorJS();

	echo "<h2>Mensajeria Privada</h2>";




	if($_GET['receptor']){
		$query=mysqli_query($link,"SELECT * FROM usuarios WHERE idusuarios='".$_GET['receptor']."'");
		$usuario=mysqli_fetch_assoc($query);
		?>
		Destinatario: <?php echo $usuario['nombre']; ?> </h2>

		<form method="POST" action="post.php">
			Mensaje:<br>
			<textarea name="mensaje_privado" cols="60" rows="2"></textarea>
			<input type="hidden" name="receptor" value="<?php echo $usuario['idusuarios']; ?>" /><br>
			<button type="button" onclick="validador('submit');">Enviar</button>
		</form>
		<?php
	}else{
		$query=mysqli_query($link,"
			SELECT *
			FROM amigos, usuarios
			WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR user2='".$global_idusuarios."' AND user1=idusuarios
		");

		if(mysqli_num_rows($query)>0){
			?>
			<style>
			.ui-autocomplete {
				        max-height: 100px;
				        overflow-y: auto;
				        /* prevent horizontal scrollbar */
				        overflow-x: hidden;
				    }
				    /* IE 6 doesn't support max-height
				     * we use height instead, but this forces the menu to always be this tall
				     */
				    * html .ui-autocomplete {
				        height: 100px;
				    }
				 </style>

			<form method='POST' action='post.php'>
				<div class="ui-widget">
				    <label for="tags">Destinatario: </label>
				    <input id="tags" name="receptor" />
				</div>


					<script>
				    $(function() {
				        var availableTags = [
						<?php
						while($row=mysqli_fetch_assoc($query)){
							echo "'".$row['nombre']." ".$row['apellidos']."',";
						}
						?>
						];
						$( "#tags" ).autocomplete({
							source: availableTags
						});
					});
					</script>
				<br>
				Mensaje:<br>
				<textarea name="mensaje_privado" cols="60" rows="2"></textarea><br>
			  	<button type="button" onclick="validador('submit');">Enviar</button>
			</form>
		<?php
		}
	}
?>