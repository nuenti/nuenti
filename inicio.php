<?php
	$tiempo_inicio = microtime(true);
	require("inc/verify_login.php");
	head("Inicio - Social");
	require("inc/estructura.php");
?>
<div class="barra_izq">
	<?php
		if($usuario['idfotos_princi']){
			$foto=mysqli_query($link,"SELECT * from fotos WHERE idfotos='".$usuario['idfotos_princi']."'");
			$foto=mysqli_fetch_assoc($foto);
			echo "<img alt='foto principal' height='50' width='50' src='".$foto['archivo']."' />";
		}
		echo $global_nombre." ".$global_apellidos;
		echo "<br>Edad: ".$usuario['edad'];
	?>
</div>



<div class="barra_centro_der" class="">
	<div class="marco_full">
		<h2>Lista de amigos</h2>
		<?php
			$query=mysqli_query($link,"

			SELECT *
	FROM amigos, usuarios
	WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR user2='".$global_idusuarios."' AND user1=idusuarios
			");
			if(mysqli_num_rows($query)>0){
				//echo "Tienes ".mysqli_num_rows($query)." peticion(es) de amistad:<br>";
				while($row=mysqli_fetch_assoc($query)){
					//print_r($row);
					echo $row['nombre']." ".$row['apellidos']." <a href='gente.php?id=".$row['idusuarios']."'>Ver perfil</a><br>";

				}
			}
		?>
	</div>


	<div class="marco_full">
		<h2>Peticiones de amistad</h2>
		<?php
			$query=mysqli_query($link,"SELECT * FROM peticiones, usuarios WHERE receptor = '".$global_idusuarios."' AND idusuarios = emisor");
			if(mysqli_num_rows($query)>0){
				echo "Tienes ".mysqli_num_rows($query)." peticion(es) de amistad:<br>";
				while($row=mysqli_fetch_assoc($query)){
					//print_r($row);
					echo $row['nombre']." ".$row['apellidos']." <a href='post.php?aceptarpeticion=1&emisor=".$row['idusuarios']."'>Aceptar peticion</a><br>";

				}
			}
		?>
		
		
		<?php
			require ("inc/sucesos.php");
		?>
	</div>
</div>
<?php
	require("chat.php"); 
	$tiempo_fin = microtime(true);
	echo "<br>Tiempo de ejecución redondeado: " . round($tiempo_fin - $tiempo_inicio, 4)."<br>";
?>
Sin novedades: 0.02 max<br>
Con consulta principal: 0.02 max