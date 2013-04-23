<?php
	require("inc/verify_login.php");

	//Comprueba amistad
	$query=mysqli_query($link,"SELECT count(*) FROM amigos WHERE user1='".$_GET['id']."' AND user2='".$global_idusuarios."' OR user2='".$_GET['id']."' AND user1='".$global_idusuarios."'");
	if(mysqli_num_rows($query)!=1){
		header("Location: inicio.php?nosoisamigos");
		die();
	}else{
		$query=mysqli_query($link,"
			SELECT *,(@tiempo:=TIME_TO_SEC(TIMEDIFF(now(),online))) AS segundos_off,
				CASE
				WHEN @tiempo<60 THEN 'conectado'
				WHEN @tiempo<86000 THEN TIME_FORMAT(TIMEDIFF(now(),online), '%H:%i:%s')
				ELSE DATE_FORMAT(online, '%d/%m/%Y %H:%i') END AS online FROM `usuarios` WHERE idusuarios='".$_GET['id']."'
		");
		$usuario=mysqli_fetch_assoc($query);
	}
	head($usuario['nombre']." - Social");
	require("inc/estructura.inc.php");


?>
<div id="barra_izq" class="">
	<?php
		if($usuario['idfotos_princi']){
			$foto=mysqli_query($link,"SELECT * from fotos WHERE idfotos='".$usuario['idfotos_princi']."'");
			$foto=mysqli_fetch_assoc($foto);
			echo "<img alt='foto principal' height='200' width='200' src='".$foto['archivo']."' />";
		}
		echo $usuario['nombre']." ".$usuario['apellidos'];
		echo "<br>Edad: ".$usuario['edad']."<br>";
		echo "<a href='mp_redactar.php?receptor=".$usuario['idusuarios']."'>Enviar mensaje privado</a><br>";
		if($usuario['online']=="conectado"){
			echo "Estado: ".$usuario['online'];
		}elseif (strlen($usuario['online'])==8) {
			echo "Ultima visita hace: ".$usuario['online'];
		}else{
			echo "Ultima visita el: ".$usuario['online'];
		}
	?>
</div>
<div class="cuerpo_der" class="">
	<a href="albums.php?iduser=<?php echo $usuario['idusuarios']; ?>">Albums de <?php echo $usuario['nombre']; ?><a/>
	<h2>Comentarios</h2>

	<form method="POST" action="post.php">
		<textarea name="comentario_tablon" cols="60" rows="2"></textarea>
		<input type="hidden" name="receptor" value="<?php echo $usuario['idusuarios']; ?>" />
		<input type="submit" value="Submit">
	</form>
	<?php
	$query=mysqli_query($link,"SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fechaf FROM tablon,usuarios WHERE receptor='".$usuario['idusuarios']."' AND idusuarios=emisor ORDER BY idtablon DESC");
	if(mysqli_num_rows($query)>0){
		while($comentarios=mysqli_fetch_assoc($query)){
			echo "<div>".$comentarios['nombre']." ".$comentarios['apellidos']." ".$comentarios['fechaf']."<br>";
			echo "Dijo: ".$comentarios['comentario']."</div><br>";
		}
	}else{
		echo "<div>".$usuario['nombre']." aun no tiene comentarios en su tablon, escribe uno!</div>";
	}
	?>
</div>

