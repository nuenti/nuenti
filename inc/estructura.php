<ul id="menudrop">
	<li><a href="inicio.php">Inicio</a></li>
	<li><a href="perfil.php">Perfil

			<?php
				$query=mysqli_query($link,"SELECT * FROM tablon WHERE receptor='".$global_idusuarios."' AND estado='nuevo'");
				if(mysqli_num_rows($query)>0){
					echo " (".mysqli_num_rows($query).")";
				}
			?>
		</a>
	</li>
	<li>
		<a href="albums.php">Albums</a>
		<ul>
			<li><a href="album.php?idalbum=subidas&amp;iduser=<?php echo $global_idusuarios; ?>">Subidas</a></li>
			<li><a href="album.php?idalbum=etiquetadas&amp;iduser=<?php echo $global_idusuarios; ?>">Etiquetadas</a></li>
			<?php
			$result=mysqli_query($link,"SELECT * FROM albums WHERE usuarios_idusuarios='".$global_idusuarios."'");
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_assoc($result)){
					echo "<li><a href='album.php?idalbum=".$row['idalbums']."&amp;iduser=".$global_idusuarios."'>".$row['album']."</a></li>";
				}
			}
			?>
		</ul>
	</li>
	<li>
		<a href="mp_entrada.php">
			Mensajes
			<?php
			$query=mysqli_query($link,"SELECT * FROM mps WHERE receptor='".$global_idusuarios."' AND estado='nuevo'");
			if(mysqli_num_rows($query)>0){
				echo " (".mysqli_num_rows($query).")";
			}
			?>
		</a>
		<ul>
			<li><a href="mp_entrada.php">Mensajes Recibidos</a></li>
			<li><a href="mp_salida.php">Mensajes Enviados</a></li>
			<li><a href="mp_redactar.php">Escribir Mensajes</a></li>
		</ul>
	</li>
	<li><a href="gente.php">Gente</a></li>
	<li><a href="subir_fotos.php">Subir fotos</a></li>
	<li style="float:right;margin-right:10px;"><a href="logout.php">Salir</a></li>
	<li style="float:right;"><a href=".php">Ajustes</a></li>
</ul>