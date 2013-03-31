<?php
	require("inc/verify_login.php");

	head("Perfil - Social");
	require("inc/estructura.php");
?>
<div class="barra_izq">
	<?php
		if($usuario['idfotos_princi']){
			$foto=mysqli_query($link,"SELECT * from fotos WHERE idfotos='".$usuario['idfotos_princi']."'");
			$foto=mysqli_fetch_assoc($foto);
			echo "<img alt='foto principal' style='max-height:200px;max-width:200px;' src='".$foto['archivo']."' />";
		}
		echo $global_nombre." ".$global_apellidos;
		echo "<br>Edad: ".$usuario['edad'];
	?>
</div>
<div class="barra_centro_der" >
	<div class="marco_full">
		<form method="POST" action="post.php" id="cambio_estado">
	
			<?php
				if($usuario['estado']){
					echo "<input type='text' name='estado' size='95' value='".$usuario['estado']."' />";
				}else{
					echo "<input type='text' name='estado' size='95' value='Actualiza tu estado' />";
				}
			?>
			<button type="submit" value="Submit">Cambiar</button>
		</form>
	</div>
	
	<div class="marco_full" >
		<h2>Comentarios</h2>
		<?php
		$query=mysqli_query($link,"SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fechaf FROM tablon,usuarios WHERE receptor='".$global_idusuarios."' AND idusuarios=emisor ORDER BY idtablon DESC");
		if(mysqli_num_rows($query)>0){
			while($comentarios=mysqli_fetch_assoc($query)){
				$div = (($comentarios['estado']=='nuevo') ? "<div style='background-color:yellow'>" : "<div>");
				echo $div.$comentarios['nombre']." dijo: ".$comentarios['comentario']." ".$comentarios['fechaf']."</div>";
			}
		}else{
			echo "<div>Aun no tienes comentarios en tu tablon</div>";
		}
		?>
	</div>
</div>


<?php
	mysqli_query($link,"UPDATE tablon SET estado='leido' WHERE receptor='".$global_idusuarios."' AND estado='nuevo'");
?>