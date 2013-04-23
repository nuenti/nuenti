<?php
	require("inc/verify_login.php");
	head("Gente - Social");
	require("inc/estructura.inc.php");
?>

<div style="float: left;width: 600px;">
<?php
	$query=mysqli_query($link,"
		SELECT *
		FROM amigos, usuarios
		LEFT JOIN fotos
		ON idfotos=idfotos_princi
		WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR user2='".$global_idusuarios."' AND user1=idusuarios
	");
	if(mysqli_num_rows($query)>0){
		while($row=mysqli_fetch_assoc($query)){
			echo "<div class='barra_izq_centro' style='width: 600px;'>";
				echo "<img alt='foto principal' height='200' width='200' src='".$row['archivo']."' />";
				echo "<a href='gente.php?id=".$row['idusuarios']."'>".$row['nombre']." ".$row['apellidos']."</a><br>";
			echo "</div>";
		}
	}else{
		?>
		<div class="barra_izq_centro" style="width: 590px;">No tienes amigos =(</div>
		<?php
	}
echo "</div>";








	if($_GET['agregar']){//ENVIAR PETICION AMISTAD
		$query=mysqli_query($link,"SELECT * FROM amigos WHERE user1='".$_GET['agregar']."' AND user2='".$global_idusuarios."' OR user1='".$global_idusuarios."' AND user2='".$_GET['agregar']."'");
		if(mysqli_errno()!=0){
			error_mysql("exit");
		}
		if(mysqli_num_rows($query)>0){
			header("Location: inicio.php?yasoisamigos");
			die(); //evitamos enviar peticion
		}
		mysqli_query($link,"INSERT INTO peticiones (emisor, receptor) VALUES ('".$global_idusuarios."','".$_GET['agregar']."')");
		if(mysqli_errno()!=0){
			error_mysql("exit");
		}
	}
	if($_GET['busqueda']){//REALIZAR BUSQUEDA
		$query=mysqli_query($link,"
		SELECT *,
			(
				SELECT count(*)
				FROM amigos WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR  user2='".$global_idusuarios."' AND user1=idusuarios
				) AS amigo,
			(
				SELECT count(*)
				FROM peticiones WHERE emisor='".$global_idusuarios."' AND receptor=idusuarios OR  receptor='".$global_idusuarios."' AND emisor=idusuarios
				) AS enviada
		FROM usuarios
		WHERE idusuarios!='".$global_idusuarios."' AND
			(nombre LIKE '%".$_GET['busqueda']."%' OR apellidos LIKE '%".$_GET['busqueda']."%')
		");

		if(mysqli_num_rows($query)>0){
			while($row=mysqli_fetch_assoc($query)){
				//print_r($row);
				echo $row['nombre']." ".$row['apellidos']." -> ";
				if($row['amigo']==0 AND $row['enviada']==0){
					echo "<a href='gente.php?busqueda=".$_GET['busqueda']."&agregar=".$row['idusuarios']."'>Agregar</a>";
				}elseif($row['amigo']==0 AND $row['enviada']==1){
					echo "Peticion enviada";
				}elseif($row['amigo']==1){
					echo "Amigo!";
				}
				echo "<br />";

			}
		}
	}
?>
</div>
<div class="barra_der" style="width: 360px;">
	Buscador de personas
	<hr>
	<form method="GET" action="gente.php">
		Nombre: <input type="text" name="busqueda" value="<?php echo $_GET['busqueda']; ?>"/><br>
		<button type="submit">Buscar</button>
	</form>
</div>