<?php
	require("inc/verify_login.php");
	head("Mensajeria Privada - Social");
	require("inc/estructura.inc.php");
?>
	<script>
	<?php
		require_once("inc/validador.class.php");
		$Validador = new Validador();
		$Validador->SetInput(array('name' => 'receptor', 'alias' => 'Destinatario', 'obligatorio' => 'si'));
		$Validador->SetInput(array('name' => 'mensaje', 'alias' => 'Mensaje', 'formato' => '[A-Za-z0-9]{1,}'));
		$Validador->GeneraValidadorJS();
	?>
		function mp_enviar(){
			if(validador()=="form_ok"){
				receptor=$("input[name='receptor_id']").val();
				mensaje=$("textarea[name='mensaje']").val();
				
				ajax_post({
					data : "mp_enviar=1&receptor=" + receptor + "&mensaje=" + mensaje,
					reload : true,
				});
			}
		}
	</script>
	
	<div class='barra_full'>
		<div class='marco'>
			<h2>Mensajeria Privada</h2>
<?php
	//Si hay un destinatario preseleccionado
	if($_GET['receptor']){
		$query=mysqli_query($link,"SELECT * FROM usuarios RIGHT JOIN fotos ON idfotos_princi=idfotos WHERE idusuarios='".$_GET['receptor']."'");
		$usuario=mysqli_fetch_assoc($query);
		
		print "<form action='#' onsubmit='return false'>
				Destinatario: 
				<img id='receptor_icon' src='{$usuario['archivo']}' style='max-width:30px;max-height:30px;vertical-align: middle;'>
				<input id='receptor' name='receptor' class='validable' value='{$usuario['nombre']} {$usuario['apellidos']}' disabled  size='40'>
				<input id='receptor_id' name='receptor_id' style='visibility: hidden;' value='{$_GET['receptor']}'>";
	}else{
		$query=mysqli_query($link,"
			SELECT idusuarios, nombre, apellidos, archivo
			FROM amigos, usuarios
			RIGHT JOIN fotos
			ON idfotos_princi=idfotos
			WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR user2='".$global_idusuarios."' AND user1=idusuarios
		");

		if(mysqli_num_rows($query)>0){
			?>
			<form action="#" onsubmit="return false">
				<div class="ui-widget">
				    <label for="receptor">Destinatario: </label>
				    <img id="receptor_icon" style='max-width:30px;max-height:30px;vertical-align: middle;'>
				    <input id="receptor" name="receptor" class="validable" autofocus size='40'>
				    <input id="receptor_id" name="receptor_id" style="visibility: hidden;" />
				</div>


					<script>
					var receptor_seleccionado=false;
				    $(function() {
						var amigos = [
							<?php
							$i_temp = 0;
							while($row=mysqli_fetch_assoc($query)){
								if ($i_temp != 0)
									echo ",";
								
								print "{
									value: '{$row['idusuarios']}',
									label: '".$row['nombre']." ".$row['apellidos']."',
									icon: '".$row['archivo']."'
								}";
								$i_temp++;
							}
							?>
						];
						
						$( "#receptor" ).autocomplete({
							source: amigos,
							minLength : 0,
							focus : function(event, ui) {
								return false;
							},
							select : function(event, ui) {
								//AL PULSAR UN RESULTADO
								receptor_seleccionado=true;
								$("#receptor").val("").addClass('input_ok').removeClass('input_error');
								$("#receptor").val(ui.item.label);
								$("#receptor_id").val(ui.item.value);
								$( "#receptor_icon" ).attr( "src",ui.item.icon );
								$("textarea[name='mensaje']").focus();
								return false;
							}
						}) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
							return $( "<li>" )
							.append( 
								"<a>"+
									"<img src='"+ item.icon + "' class='autocomplete_img'>"+
										"<div class='autocomplete_label'>" + item.label + "</div>"+
									"</a>" )
							.appendTo( ul );
						};
					});
					$(window).ready(function(){
						$( "#receptor" ).autocomplete("search");
						
						//Aseguramos la seleccion del autocompletado
						$("#receptor").blur(function(e){
							if(!receptor_seleccionado){
								$("#receptor").val("").addClass('input_error').removeClass('input_ok');
							}
						});
						$("#receptor").focus(function(e){
						$( "#receptor" ).autocomplete("search");
						});
					});
					</script>
		<?php
		}else{
			echo "No tienes amigos";
		}
	}
	?>
	<br>
	Mensaje:<br>
	<textarea name="mensaje" class="validable" cols="60" rows="2"></textarea><br>
	<button type="button" onclick="mp_enviar();">Enviar</button>
</form>